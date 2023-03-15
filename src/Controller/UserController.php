<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserSearch;
use App\Form\ChangePasswordFormType;
use App\Form\UserEditType;
use App\Form\UserSearchType;
use App\Form\UserType;
use App\Notification\UserNotification;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin")
 */

class UserController extends AbstractController
{
    private $repository;

	public function __construct(UserRepository $repository) 
	{
		$this->repository = $repository;
    }
    
    /**
     * @Route("/users", name="users")
     */
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $search = new UserSearch();
        $form = $this->createForm(UserSearchType::class, $search, array(
			'attr' => ['id' =>'search']
		));
		$form->handleRequest($request);

        if ($request->get('orderBy')) {
			$search->setOrderBy($request->get('orderBy'));
			$search->setOrder($request->get('order'));
		} else {
			$search->setOrderBy('lastname');
			$search->setOrder('ASC');
        }
        
        $users = $paginator->paginate(
			$this->repository->findAllQuery($search),
			$request->query->getInt('page', 1),
			15
        );
        
        return $this->render('users/index.html.twig', array(
			'users' => $users,
			'form' => $form->createView(),
			'search' => $search
		));
    }

    /**
     * @Route("/users/{id}", name="user_show", requirements = {"id"="\d+"})
	 * @ParamConverter("user", class="App\Entity\User")
	 */
	public function showAction(User $user)
	{
		return $this->render('users/show.html.twig', array(
			'user' => $user
		));
	}

	/**
	 * @Route("/users/add", name="user_add")
	 */
	public function addAction(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, UserNotification $notification)
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$createdAt = new \DateTime();
			$user->setCreatedAt($createdAt);
			$user->setIsActive(true);
			$user->setRoles($user->getDepartment()->getRoles());

			$notification->newUserNotify($user);

			$user->setPassword($userPasswordEncoder->encodePassword($user, $user->getPassword()));

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			return $this->redirectToRoute('user_show', array(
				'id' => $user->getId()
			));
		}

		return $this->render('users/add.html.twig', array(
			'form' => $form->createView(),
			'submitText' => 'Ajouter'
		));
	}

	/**
	 * @Route("/users/edit/{id}", name="user_edit", requirements = {"id"="\d+"})
	 * @ParamConverter("user", class="App\Entity\User")
	 */
	public function editAction(User $user, Request $request)
	{
		$form = $this->createForm(UserEditType::class, $user);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em = $this->getDoctrine()->getManager();

			if (!$user->getIsActive()) {
				if ($user == $this->getUser()) {
					$this->addFlash('notice', 'Vous ne pouvez pas désactiver votre propre compte.');
					return $this->redirectToRoute('user_show', array(
						'id' => $user->getId()
					));
				}
				$user->setRoles(["INACTIF"]);
			} else {
				$user->setRoles($user->getDepartment()->getRoles());
			}
			
			$em->flush();

			$this->addFlash('notice', 'Modification éffectuée');

			return $this->redirectToRoute('user_show', array(
				'id' => $user->getId()
			));
		}

		return $this->render('users/edit.html.twig', array(
			'form' => $form->createView(),
			'submitText' => 'Modifier'
		));
	}

	/**
	 * @Route("/users/edit-password/{id}", name="user_edit_password", requirements = {"id"="\d+"})
	 * @ParamConverter("user", class="App\Entity\User")
	 */
	public function editPasswordAction(User $user, Request $request, UserPasswordEncoderInterface $userPasswordEncoder)
	{
		$form = $this->createForm(ChangePasswordFormType::class);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$user->setPassword($userPasswordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));

			$em = $this->getDoctrine()->getManager();
			$em->flush();

			$this->addFlash('notice', 'Mot de passe modifié');

			return $this->redirectToRoute('user_show', array(
				'id' => $user->getId()
			));
		}

		return $this->render('reset_password/reset.html.twig', array(
			'resetForm' => $form->createView()
		));
	}

	/**
	 * @Route("/users/delete/{id}", name="user_delete", requirements = {"id"="\d+"})
	 * @ParamConverter("user", class="App\Entity\User")
	 */
	public function deleteAction(User $user)
	{
		if (!$user) {
			throw $this->createNotFoundException(
				'Pas de salarié correspondant !'
			);
		}

		if ($user == $this->getUser()) {
			throw new UnauthorizedHttpException("Il ne vous est pas permis de supprimer votre propre compte utilisateur !");
		}

		$em = $this->getDoctrine()->getManager();
		$em->remove($user);
		$em->flush();

		$this->addFlash('notice', $user->getLastname() . ' ' . $user->getFirstname() .  ' a bien été supprimé');

		return $this->redirectToRoute('users');
	}
}
