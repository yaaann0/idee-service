<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $user = $this->getUser();
        if ($user) {
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return $this->redirectToRoute('schedules_admin');
            }
            if (in_array('ROLE_USER', $user->getRoles())) {
                return $this->redirectToRoute('schedules');
            }
        }        

        return $this->redirectToRoute('app_login');
    }
}
