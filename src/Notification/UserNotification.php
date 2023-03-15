<?php

namespace App\Notification;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class UserNotification  
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

    public function newUserNotify(User $user)
    {
        $message = (new Email())
            ->from(new Address('noreply@ideeservicesauvergne.fr', 'IDEE SERVICES ESPACE SALARIES'))
            ->to($this->accountingEmail)
            ->subject('Nouveau compte utilisateur dans l\'espace salarié')
            ->html($this->renderer->render('emails/new_user.html.twig', array(
                'user' => $user
            )), 'text/html');

        $this->mailer->send($message);
    }
}
