<?php

namespace App\Notification;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class GrantNotification  
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

    public function mealNotify(User $user, $attachment)
    {
        $subject = (new \DateTime())->format('Ymd').'-'.$user->getLastname().' '.$user->getFirstname().' - indemnites repas';

        $message = (new Email())
            ->from(new Address($user->getEmail(), $user->getLastname().' '.$user->getFirstname()))
            ->replyTo($user->getEmail())
            ->to($this->accountingEmail)
            ->subject($subject)
            ->html($this->renderer->render('emails/meal_notification.html.twig', array(
                    'user' => $user
                )), 'text/html')
            ->attach($attachment, $subject.'.pdf', 'application/pdf');

        $this->mailer->send($message);
    }

    public function journeyNotify(User $user, $attachment)
    {
        $subject = (new \DateTime())->format('Ymd').'-'.$user->getLastname().' '.$user->getFirstname().' - indemnites kilométriques';

        $message = (new Email())
            ->from(new Address($user->getEmail(), $user->getLastname().' '.$user->getFirstname()))
            ->replyTo($user->getEmail())
            ->to($this->accountingEmail)
            ->subject($subject)
            ->html($this->renderer->render('emails/journey_notification.html.twig', array(
                    'user' => $user
                )), 'text/html')
            ->attach($attachment, $subject.'.pdf', 'application/pdf');

        $this->mailer->send($message);
    }
}
