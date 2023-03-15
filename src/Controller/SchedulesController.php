<?php

namespace App\Controller;

use App\Entity\Weeksheet;
use App\Entity\WeeksheetSearch;
use App\Form\ScheduleType;
use App\Form\WeeksheetSearchType;
use App\Notification\SchedulesNotification;
use App\Repository\NewsRepository;
use App\Repository\SheetStateRepository;
use App\Repository\UserRepository;
use App\Repository\WeeksheetRepository;
use App\Repository\WorkDayRepository;
use App\Service\PDFConverter;
use App\Service\TaskService;
use App\Service\WeeksheetProvider;
use App\Service\WorkDayService;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class SchedulesController extends AbstractController
{
	private $weekRepository;
	private $dayRepository;
	private $newsRepository;
	private $sheetStateRepository;

	public function __construct(WeeksheetRepository $weekRepository, WorkDayRepository $dayRepository, NewsRepository $newsRepository, SheetStateRepository $sheetStateRepository) 
	{
		$this->weekRepository = $weekRepository;
		$this->dayRepository = $dayRepository;
		$this->newsRepository = $newsRepository;
		$this->sheetStateRepository = $sheetStateRepository;
    }
    
    /**
     * @Route("/app/schedules", name="schedules")
     */
    public function index(PaginatorInterface $paginator, WeeksheetProvider $provider, Request $request)
    {
		if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
			return $this->redirectToRoute('schedules_admin');
		}
	
		$form = $this->createFormBuilder(null)
			->add('week', ChoiceType::class, array(
				'constraints' => [
                    new NotBlank(),
                    new Type(Weeksheet::class),
                ],
                'label' => 'Semaine :',
                'choices' => $provider->getAddable(),
                'expanded' => false,
                'multiple' => false
            ))
			->getForm()
		;
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$weeksheet = $provider->manualAdd(
				$form->getData()['week'],
				$this->getUser()
			);

			return $this->redirectToRoute('schedules_edit', array(
				'id' => $weeksheet->getId()
			));
		}

		$existingWeeksheets = $provider->provide($this->getUser());

		$weeksheets = $paginator->paginate(
			$existingWeeksheets,
			$request->query->getInt('page', 1),
			10
		);

		$lastNews = $this->newsRepository->findBy([], ['createdAt' => 'DESC'], 3);

        return $this->render('schedules/index.html.twig', [
			'weeksheets' => $weeksheets,
			'lastNews' => $lastNews,
			'form' => $form->createView()
        ]);
	}
	
	/**
	 * @Route("/admin/schedules", name="schedules_admin")
	 */
	public function adminIndex(PaginatorInterface $paginator, Request $request, UserRepository $userRepository)
	{
		$search = new WeeksheetSearch();
		$form = $this->createForm(WeeksheetSearchType::class, $search);

		$form->handleRequest($request);

        if ($request->get('orderBy')) {
			$search->setOrderBy($request->get('orderBy'));
			$search->setOrder($request->get('order'));
		} else {
			$search->setOrderBy('beginAt');
			$search->setOrder('DESC');
		}
		
		$weeksheets = $paginator->paginate(
			$this->weekRepository->findAllQuery($search, $userRepository),
			$request->query->getInt('page', 1),
			20
		);

		foreach ($weeksheets as $weeksheet) {
			$weeksheet->setDuration();
		}

		/* $states = $this->sheetStateRepository->findAll(); */
		
		return $this->render('schedules/index_admin.html.twig', array(
			/* 'states' => $states, */
			'weeksheets' => $weeksheets,
			'form' => $form->createView(),
			'search' => $search
		));
	}

    /**
     * @Route("/app/schedules/{id}", name="schedules_show", requirements = {"id"="\d+"})
	 * @ParamConverter("weeksheet", class="App\Entity\Weeksheet")
     */
	public function showAction(Weeksheet $weeksheet, WorkDayService $workDayService, Request $request, SchedulesNotification $notification, PDFConverter $converter)
	{
		$isAdmin = in_array('ROLE_ADMIN', $this->getUser()->getRoles());
		if (!$isAdmin && $this->getUser() != $weeksheet->getUser()) {
			throw new UnauthorizedHttpException("Vous n'avez pas accès à cette page.");
		}

		$durations = $workDayService->getDaysDurations($weeksheet);

		$isValidated = $weeksheet->getState()->getSlug() == 'validated';

		$form = $this->createFormBuilder(null)
			->add('validate', SubmitType::class, array(
				'label' => $isValidated ? 'Signer' : 'Valider',
				'attr' => array(
					'class' => 'btn btn-success',
					'id' => 'validate',
					'value' => 'validate'
				)
			))
			->getForm()
		;
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {

			if ($isValidated && $isAdmin) {
				$admin = $this->getUser();

				$weeksheet->setState($this->sheetStateRepository->findOneBy(['slug'=> 'signed']));
				$weeksheet->setValidator($admin);
				$weeksheet->setSignedAt(new DateTime());

				$em = $this->getDoctrine()->getManager();
				$em->flush();

				try {
					$attachment = $converter->generateWeeksheet($weeksheet, $durations);
					$notification->signedNotify($weeksheet, $attachment, $admin);
					$this->addFlash('notice', 'Feuille d\'heures signée et envoyée !');
				} catch (\Exception $e) {
					throw $e;
					$this->addFlash('errors', $e->getMessage());
				}
						
			} else {
				$weeksheet->setState($this->sheetStateRepository->findOneBy(['slug'=> 'validated']));
				$em = $this->getDoctrine()->getManager();
				$em->flush();
				if (!$isAdmin) {
					$this->addFlash('notice', 'Feuille d\'heures validée !');
					return $this->redirectToRoute('schedules');
				}
			}


			return $this->redirectToRoute('schedules_show', array(
				'id' => $weeksheet->getId()
			));
		}

		return $this->render('schedules/show.html.twig', array(
			'weeksheet' => $weeksheet,
			'durations' => $durations,
			'HTMLRender' => true,
			'form' => $form->createView()
		));
	}

	/**
     * @Route("/app/schedules/edit/{id}", name="schedules_edit", requirements = {"id"="\d+"})
	 * @ParamConverter("weeksheet", class="App\Entity\Weeksheet")
     */
	public function editAction(Weeksheet $weeksheet, TaskService $taskService, Request $request, SchedulesNotification $notification, PDFConverter $converter, WorkDayService $workDayService)
	{	
		if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles()) && ($weeksheet->getState()->getSlug() != 'draft' || $this->getUser() != $weeksheet->getUser())) {
			return $this->redirectToRoute('schedules_show', array(
				'id' => $weeksheet->getId()
			));
		}

		$alreadyUpdated = $weeksheet->getIsUpdated();

		$taskService->addEmptyTask($weeksheet);

		$form = $this->createForm(ScheduleType::class, $weeksheet);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$taskService->submitTasks($weeksheet, $this->getUser());

			if ($form->get('unvalidate')->isClicked() && in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
				$weeksheet->setIsUpdated(true);
				$weeksheet->setState($this->sheetStateRepository->findOneBy(['slug'=>'draft']));
				$this->addFlash('notice', 'Cette feuille a été rendue modifiable');

				$notification->notifyUserOfAdminUpdate($weeksheet, 'Votre feuille d\'heures a été rendue modifiable par un administrateur');
			}

			$em = $this->getDoctrine()->getManager();
			$em->flush();
			
			if ($form->get('save')->isClicked()) {
				$this->addFlash('notice', 'Modifications enregistrées');

				if (!$alreadyUpdated && $weeksheet->getIsUpdated()) {
					$notification->notifyUserOfAdminUpdate($weeksheet, 'Votre feuille d\'heures a été modifiée par un administrateur');
				}

				if ($weeksheet->getState()->getSlug()== 'signed') {
					$durations = $workDayService->getDaysDurations($weeksheet);
					$attachment = $converter->generateWeeksheet($weeksheet, $durations);
					$admin = $this->getUser();
					$notification->signedNotify($weeksheet, $attachment, $admin);
				}
			}
			
			if ($form->get('validate')->isClicked()) {
				if ($weeksheet->getDuration()|| $weeksheet->getComment()) {
					return $this->redirectToRoute('schedules_show', array(
						'id' => $weeksheet->getId()
					));
				}

				$this->addFlash('errors', 'Total heures hebdo = 0, merci de préciser la raison (congés, absence ou autre)');
			}

		}

		$recentWeeksheets = $this->weekRepository->findBy(
			array('user' => $weeksheet->getUser()),
			array('beginAt' => 'DESC'),
			9
		);

		return $this->render('schedules/edit.html.twig', array(
			'weeksheet' => $weeksheet,
			'user' => $weeksheet->getUser(),
			'form' => $form->createView(),
			'recentWeeksheets' => $recentWeeksheets
		));
	}

	/**
     * @Route("/app/schedules/copy/{origin}/{target}", name="schedules_copy", requirements = {"origin"="\d+", "target"="\d+"})
	 * @ParamConverter("origin", class="App\Entity\Weeksheet")
	 * @ParamConverter("target", class="App\Entity\Weeksheet")
     */
	public function copyAction(Weeksheet $origin, Weeksheet $target, TaskService $taskService)
	{
		if (!$origin || !$target) {
			throw $this->createNotFoundException(
				'Semaine demandée introuvable !'
			);
		}

		$originDays = $this->dayRepository->findBy(['weeksheet' => $origin]);
		$targetDays = $this->dayRepository->findBy(['weeksheet' => $target]);

		foreach ($targetDays as $targetDay) {
			foreach ($originDays as $originDay) {
				if ($targetDay->getDatetime()->format('w') == $originDay->getDatetime()->format('w')) {
					$taskService->copyTasks($originDay, $targetDay);
				}
			}
		}

		return $this->redirectToRoute('schedules_edit', array(
			'id' => $target->getId()
		));
	}

	/**
     * @Route("/admin/schedules/download/{id}", name="schedules_download", requirements = {"id"="\d+"})
	 * @ParamConverter("weeksheet", class="App\Entity\Weeksheet")
     */
	public function download(Weeksheet $weeksheet, PDFConverter $converter, WorkDayService $workDayService)
	{
		$durations = $workDayService->getDaysDurations($weeksheet);
		$file = $converter->getWeeksheet($weeksheet, $durations);
		
		$filename = $weeksheet->getBeginAt()->format("Y-m").'-Semaine '.$weeksheet->getBeginAt()->format("W").' - '.$weeksheet->getUser()->getLastname().' '.$weeksheet->getUser()->getFirstname();

		$response = new Response($file, Response::HTTP_OK, array(
            'Content-Type' => 'application/pdf',
            'Content-Length' => strlen($file),
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ));
		
        return $response;
	}

	/**
	 * @Route("/admin/schedules/set-state/{page}", name="shedule_set_tmp", requirements = {"page"="\d+"})
	 */
	public function FunctionName(int $page): Response
	{
		$limit = 500;
		$weeksheets = $this->weekRepository->findBy(
			array('state'=>null),
			array('beginAt'=> 'ASC'),
			$limit,
			($page-1)*$limit
		);

		$draft = $this->sheetStateRepository->findOneBy(['slug'=>'draft']);
		$validated = $this->sheetStateRepository->findOneBy(['slug'=>'validated']);
		$updated = $this->sheetStateRepository->findOneBy(['slug'=>'updated']);

		foreach ($weeksheets as $key => $value) {
			if ($value->getIsValidated() ) {
				$state = $validated;
			} else if ($value->getIsUpdated()) {
				$state = $updated;
			} else {
				$state = $draft;
			}

			$value->setState($state);

		}
		$em = $this->getDoctrine()->getManager();
		$em->flush();

		dd($weeksheets);

		return new JsonResponse($weeksheets);
	}
}
