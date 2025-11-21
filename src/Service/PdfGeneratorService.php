<?php

namespace App\Service;

use App\Entity\MealPlan;
use Twig\Environment;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfGeneratorService
{
    public function __construct(
        private Environment $twig
    ) {}

    public function generateMealPlanPdf(MealPlan $mealPlan): string
    {
        // Configure Dompdf
        $options = new Options();
        $options->set('defaultFont', 'sans-serif');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        // Render the Twig template to HTML
        $html = $this->twig->render('pdf/meal_plan.html.twig', [
            'mealPlan' => $mealPlan,
        ]);

        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Get the PDF output
        $output = $dompdf->output();

        // Generate a unique filename
        $filename = sprintf('meal_plan_%s_%s.pdf', $mealPlan->getUser()->getId(), (new \DateTime())->format('YmdHis'));
        $filePath = sys_get_temp_dir() . '/' . $filename;

        // Save the PDF to a temporary file
        file_put_contents($filePath, $output);

        return $filePath;
    }
}
