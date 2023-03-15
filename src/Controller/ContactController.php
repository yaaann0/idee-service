<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route(path = "/app/contact", name="contact")
     */
    public function contactAction(ContactNotification $notification, Request $request)
    {
        $contact = new Contact();
        $contact->setUser($this->getUser());

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setContent(htmlspecialchars($contact->getContent()), ENT_HTML5);
            $notification->notify($contact);

            $this->addFlash('notice', 'Message envoyé');

            return $this->redirectToRoute('schedules');
        }
        
        return $this->render('contact/form.html.twig', array(
            'form' => $form->createView()
        ));


    }
}
