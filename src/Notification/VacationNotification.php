<?php

namespace App\Notification;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class VacationNotification  
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

    public function notify(User $user, $attachment)
    {
        $subject = (new \DateTime())->format('Ymd').'-'.$user->getLastname().' '.$user->getFirstname().'- demande de congés';

        $message = (new Email())
            ->from(new Address($user->getEmail(), $user->getLastname().' '.$user->getFirstname()))
            ->replyTo($user->getEmail())
            ->to($this->accountingEmail)
            ->subject($subject)
            ->html($this->renderer->render('emails/vacation_notification.html.twig', array(
                    'user' => $user
                )), 'text/html')
            ->attach($attachment, $subject.'.pdf', 'application/pdf');

        $this->mailer->send($message);
    }
}
