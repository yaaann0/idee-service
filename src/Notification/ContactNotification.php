<?php

namespace App\Notification;

use App\Entity\Contact;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ContactNotification  
{
    private $mailer;
    private $renderer;
    private $accountingEmail;

    public function __construct(MailerInterface $mailer, Environment $renderer, $accountingEmail) 
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
        $this->accountingEmail = $accountingEmail;
    }

    public function notify(Contact $contact)
    {
        $from = $contact->getUser();

        $message = (new Email())
            ->from(new Address($from->getEmail(), $from->getLastname().' '.$from->getFirstname()))
            ->replyTo($from->getEmail())
            ->to($this->accountingEmail)
            ->subject($contact->getSubject())
            ->html($this->renderer->render('emails/contact.html.twig', array(
                'contact' => $contact
            )), 'text/html');

        $this->mailer->send($message);
    }
}
