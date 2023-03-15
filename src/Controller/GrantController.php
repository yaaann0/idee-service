<?php

namespace App\Controller;

use App\Entity\JourneySheet;
use App\Entity\MealSheet;
use App\Entity\Sector;
use App\Form\JourneySheetType;
use App\Form\MealSheetType;
use App\Notification\GrantNotification;
use App\Service\PDFConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrantController extends AbstractController
{
    /**
     * @Route("/app/repas", name="meal")
     */
    public function mealAction(Request $request, GrantNotification $notification, PDFConverter $converter): Response
    {
        $mealSheet = new MealSheet();

        $form = $this->createForm(MealSheetType::class, $mealSheet);
        $form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

            $sectors = $this->getDoctrine()->getRepository(Sector::class)->findAll();

            try
            {
                $attachment = $converter->generateMealSheet($mealSheet, $this->getUser(), $sectors);
                $notification->mealNotify($this->getUser(), $attachment);
                
                $this->addFlash('notice', 'Feuille d\'indemnités repas envoyée');
                return $this->redirectToRoute('schedules');
            } 
            catch (\Exception $e) {
				throw $e;
				$this->addFlash('errors', $e->getMessage());
			}	

        }

        return $this->render('meal_grant/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/app/trajets", name="journey")
     */
    public function journeyAction(Request $request, GrantNotification $notification, PDFConverter $converter): Response
    {
        if (!in_array('ROLE_USER_2', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('schedules');
        }

        $journeySheet = new JourneySheet();

        $form = $this->createForm(JourneySheetType::class, $journeySheet);
        $form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

            $sectors = $this->getDoctrine()->getRepository(Sector::class)->findAll();

            try
            {
                $attachment = $converter->generateJourneySheet($journeySheet, $this->getUser(), $sectors);
                $notification->JourneyNotify($this->getUser(), $attachment);
                
                $this->addFlash('notice', 'Feuille d\'indemnités kilométriques envoyée');
                return $this->redirectToRoute('schedules');
            } 
            catch (\Exception $e) {
				throw $e;
				$this->addFlash('errors', $e->getMessage());
			}	

        }

        return $this->render('journey_grant/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
