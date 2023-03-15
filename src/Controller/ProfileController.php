<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditPasswordType;
use App\Security\FormLoginAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/app")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

	/**
	 * @Route("/profile/edit-password", name="profile_edit_password")
	 */
	public function editPasswordAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
	{
		$form = $this->createForm(UserEditPasswordType::class);
        $form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            if ($passwordEncoder->isPasswordValid($user, $form->get('currentPassword')->getData())) {  
       
                $user->setPassword($passwordEncoder->encodePassword($user, $form->get('newPassword')->getData()));

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->addFlash('notice', 'Mot de passe modifié');

                return $this->redirectToRoute('profile');
                
            } else {
                $form->addError(new FormError('Mot de passe actuel incorrect'));
            }
		}

		return $this->render('profile/edit_password.html.twig', array(
			'form' => $form->createView()
		));
	}
}
