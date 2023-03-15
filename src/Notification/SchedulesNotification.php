<?php

namespace App\Notification;

use App\Entity\User;
use App\Entity\Weeksheet;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class SchedulesNotification  
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

    public function notify(Weeksheet $weeksheet, $attachment)
    {
        $subject = $weeksheet->getBeginAt()->format("Y-m").'-Semaine '.$weeksheet->getBeginAt()->format("W").' - '.$weeksheet->getUser()->getLastname().' '.$weeksheet->getUser()->getFirstname();

        $message = (new Email())
            ->from(new Address($this->accountingEmail, 'IDEE SERVICES ESPACE SALARIES'))
            ->to($this->accountingEmail)
            ->replyTo($weeksheet->getUser()->getEmail())
            ->subject($subject)
            ->html($this->renderer->render('emails/schedule_notification.html.twig', array(
                    'weeksheet' => $weeksheet
                )), 'text/html')
            ->attach($attachment, $subject.'.pdf', 'application/pdf');

        $this->mailer->send($message);
    }

    public function signedNotify(Weeksheet $weeksheet, string $attachment)
    {
        $subject = $weeksheet->getBeginAt()->format("Y-m").'-Semaine '.$weeksheet->getBeginAt()->format("W").' - '.$weeksheet->getUser()->getLastname().' '.$weeksheet->getUser()->getFirstname();

        $message = (new Email())
            ->from(new Address($this->accountingEmail, 'IDEE SERVICES ESPACE SALARIES'))
            ->to($this->accountingEmail)
            ->replyTo($weeksheet->getUser()->getEmail())
            ->subject($subject)
            ->html($this->renderer->render('emails/signed_schedule_notification.html.twig', array(
                    'weeksheet' => $weeksheet
                )), 'text/html')
            ->attach($attachment, $subject.'.pdf', 'application/pdf');

        $this->mailer->send($message);
    }

    public function notifyUserOfAdminUpdate(Weeksheet $weeksheet, string $message)
    {
        $from = new Address($this->accountingEmail, 'IDEE SERVICES ESPACE SALARIES');
        $message = (new Email())
            ->from($from)
            ->to($weeksheet->getUser()->getEmail())
            ->replyTo($from)
            ->subject('Votre feuille d\'heure de la semaine '. $weeksheet->getBeginAt()->format("W") . ' a été modifiée')
            ->html($this->renderer->render('emails/updated_schedule_notification.html.twig', array(
                    'weeksheet' => $weeksheet,
                    'message' => $message
                )), 'text/html');

        $this->mailer->send($message);
    }
}
