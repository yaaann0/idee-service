<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Repository\NewsRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class NewsController extends AbstractController
{
    private $repository;

	public function __construct(NewsRepository $repository) 
	{
		$this->repository = $repository;
    }
    
    /**
     * @Route("app/news", name="news")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $newsList = $paginator->paginate(
            $this->repository->findAllQuery(),
            $request->query->getInt('page', 1),
			20
        );

        return $this->render('news/index.html.twig', [
            'newsList' => $newsList,
        ]);
    }

    /**
	 * @Route("/app/news/{id}", name="news_show", requirements = {"id"="\d+"})
	 * @ParamConverter("news", class="App\Entity\News")
	 */
	public function showAction(News $news)
	{
		return $this->render('news/show.html.twig', array(
			'news' => $news
		));
	}

    /**
     * @Route("admin/news/add", name="news_add")
     */
    public function addAction(Request $request)
    {
        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			$createdAt = new \DateTime();
			$news->setCreatedAt($createdAt);
			$em = $this->getDoctrine()->getManager();
			$em->persist($news);
			$em->flush();

			return $this->redirectToRoute('news_show', array(
				'id' => $news->getId()
			));
		}

		return $this->render('news/add.html.twig', array(
			'form' => $form->createView(),
			'submitText' => 'Ajouter'
		));
    }

    /**
	 * @Route("/admin/news/edit/{id}", name="news_edit", requirements = {"id"="\d+"})
	 * @ParamConverter("news", class="App\Entity\News")
	 */
	public function editAction(News $news, Request $request)
	{
		$form = $this->createForm(NewsType::class, $news);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$this->addFlash('notice', 'Modification effectuée');

			return $this->redirectToRoute('news_show', array(
				'id' => $news->getId()
			));
		}

		return $this->render('news/edit.html.twig', array(
			'form' => $form->createView(),
			'submitText' => 'Modifier'
		));
    }
    
    /**
     * @Route("/app/news/files/{slug}", name="news_file")
     */
    public function downloadAction($slug, Request $request)
    {
        $filePath = $this->getParameter('kernel.project_dir').'/files/news/'.$slug;

        if (file_exists($filePath)) {
            $stream = new Stream($filePath, false);
            $response = new BinaryFileResponse($stream);
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
            return $response;
        }

        $this->addFlash('errors', 'Le fichier demandé est introuvable');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
	 * @Route("/admin/news/delete/{id}", name="news_delete", requirements = {"id"="\d+"})
	 * @ParamConverter("news", class="App\Entity\News")
	 */
	public function deleteAction(News $news)
	{
		if (!$news) {
			throw $this->createNotFoundException(
				'Pas d\'actualités correpondante !'
			);
		}

		$em = $this->getDoctrine()->getManager();
		$em->remove($news);
		$em->flush();

		$this->addFlash('notice', 'Actualité supprimée');

		return $this->redirectToRoute('news');
	}
}
