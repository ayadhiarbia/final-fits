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
    ) {
    }

    public function generateMealPlanPdf(MealPlan $mealPlan): string
    {
        // Configure Dompdf options
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('tempDir', sys_get_temp_dir());
        // Removed fontDir and fontCache since they require projectDir

        $dompdf = new Dompdf($options);

        // FIXED: Updated template path to match your actual template
        $html = $this->twig->render('pdf/meal_plan.html.twig', [
            'meal_plan' => $mealPlan,
        ]);

        $dompdf->loadHtml($html);

        // Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Generate a unique filename
        $filename = sprintf('meal_plan_%s_%s.pdf',
            $mealPlan->getId(),
            (new \DateTime())->format('Ymd_His')
        );
        $filePath = sys_get_temp_dir() . '/' . $filename;

        // Save the PDF to a temporary file
        file_put_contents($filePath, $dompdf->output());

        return $filePath;
    }
}
