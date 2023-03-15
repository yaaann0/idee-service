<?php

namespace App\Controller;

use App\Entity\VacationSheet;
use App\Form\VacationSheetType;
use App\Notification\VacationNotification;
use App\Service\PDFConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VacationController extends AbstractController
{
    /**
     * @Route("/app/conges", name="vacation")
     */
    public function index(Request $request, VacationNotification $notification, PDFConverter $converter): Response
    {
        $vacationSheet = new VacationSheet();
        $vacationSheet->setUser($this->getUser());

        $form = $this->createForm(VacationSheetType::class, $vacationSheet);
		$form->handleRequest($request);
        
		if ($form->isSubmitted() && $form->isValid()) {

            foreach ($vacationSheet->getVacations() as $vc) {
                if (!$vc->getSecond()->getBeginAt() || !$vc->getSecond()->getFinishAt()) {
                    $vc->setSecond(null);
                }
            }

            try {
                $attachment = $converter->generateVacationSheet($vacationSheet);
                $notification->notify($this->getUser(), $attachment);
                
                $this->addFlash('notice', 'Feuille de demande de congés envoyée');
                return $this->redirectToRoute('schedules');
            } 
            catch (\Exception $e) {
                throw $e;
                $this->addFlash('errors', $e->getMessage());
            }	
        }

        return $this->render('vacation/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
