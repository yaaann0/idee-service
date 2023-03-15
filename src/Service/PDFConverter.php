<?php

namespace App\Service;

use App\Entity\JourneySheet;
use App\Entity\MealSheet;
use App\Entity\User;
use App\Entity\VacationSheet;
use App\Entity\Weeksheet;
use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use Twig\Environment;

class PDFConverter
{
    private $renderer;
    private $tmpDirectory;
    private $mealGrant;

    public function __construct(Environment $renderer, $tmpDirectory, $mealGrant) {
        $this->renderer = $renderer;
        $this->tmpDirectory = $tmpDirectory;
        $this->mealGrant = (float)$mealGrant;
    }

    public function generateWeeksheet(Weeksheet $weeksheet, array $durations)
    {
        $html = $this->renderer->render('schedules/show.pdf.twig', array(
            'weeksheet' => $weeksheet,
            'durations' => $durations,
            'HTMLRender' => false
        ));

        $filename = $weeksheet->getBeginAt()->format("Y-m").'-Semaine '.$weeksheet->getBeginAt()->format("W").' - '.$weeksheet->getUser()->getLastname().' '.$weeksheet->getUser()->getFirstname();

        $mpdf = new Mpdf(array(
            'orientation' => 'P',
            'tempDir' => $this->tmpDirectory,
            'margin_left' => 7,
			'margin_right' => 7,
			'margin_top' => 16,
			'margin_bottom' => 10,
        ));

        $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        return $mpdf->Output($filename.'.pdf', 'S');
    }

    public function getWeeksheet(Weeksheet $weeksheet, array $durations)
    {
        $html = $this->renderer->render('schedules/show.pdf.twig', array(
            'weeksheet' => $weeksheet,
            'durations' => $durations,
            'HTMLRender' => false
        ));

        $filename = $weeksheet->getBeginAt()->format("Y-m").'-Semaine '.$weeksheet->getBeginAt()->format("W").' - '.$weeksheet->getUser()->getLastname().' '.$weeksheet->getUser()->getFirstname();

        $mpdf = new Mpdf(array(
            'orientation' => 'P',
            'tempDir' => $this->tmpDirectory,
            'margin_left' => 7,
			'margin_right' => 7,
			'margin_top' => 16,
			'margin_bottom' => 10,
        ));

        $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        return $mpdf->Output($filename.'.pdf', true);;
    }

    public function generateMealSheet(MealSheet $sheet, User $user, array $sectors)
    {
        $html = $this->renderer->render('meal_grant/show.pdf.twig', array(
            'sheet' => $sheet,
            'user' => $user,
            'sectors' => $sectors,
            'cost' => $this->mealGrant
        ));

        $mpdf = new Mpdf(array(
            'orientation' => 'L',
            'tempDir' => $this->tmpDirectory,
            'margin_left' => 7,
			'margin_right' => 7,
			'margin_top' => 16,
			'margin_bottom' => 10,
        ));

        $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        return $mpdf->Output(uniqid().'.pdf', 'S');
    }

    public function generateJourneySheet(JourneySheet $sheet, User $user, array $sectors)
    {
        $html = $this->renderer->render('journey_grant/show.pdf.twig', array(
            'sheet' => $sheet,
            'user' => $user,
            'sectors' => $sectors,
        ));

        $mpdf = new Mpdf(array(
            'orientation' => 'L',
            'tempDir' => $this->tmpDirectory,
            'margin_left' => 7,
			'margin_right' => 7,
			'margin_top' => 16,
			'margin_bottom' => 10,
        ));

        $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        return $mpdf->Output(uniqid().'.pdf', 'S');
    }

    public function generatevacationSheet(VacationSheet $sheet)
    {
        $html = $this->renderer->render('vacation/show.pdf.twig', array(
            'sheet' => $sheet,
        ));

        $mpdf = new Mpdf(array(
            'orientation' => 'L',
            'tempDir' => $this->tmpDirectory,
            'margin_left' => 7,
			'margin_right' => 7,
			'margin_top' => 16,
			'margin_bottom' => 10,
        ));

        $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        return $mpdf->Output(uniqid().'.pdf', 'S');
    }
}

