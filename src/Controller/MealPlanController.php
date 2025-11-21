<?php

namespace App\Controller;

use App\Entity\MealPlan;
use App\Service\MealPlanGeneratorService;
use App\Service\PdfGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/meal-plan')]
#[IsGranted('ROLE_USER')]
class MealPlanController extends AbstractController
{
    #[Route('/', name: 'app_meal_plan_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $mealPlans = $entityManager->getRepository(MealPlan::class)->findBy(['user' => $user], ['id' => 'DESC']);

        return $this->render('meal_plan/index.html.twig', [
            'meal_plans' => $mealPlans,
        ]);
    }

    #[Route('/generate', name: 'app_meal_plan_generate')]
    public function generate(MealPlanGeneratorService $generatorService): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Simple logic to generate a plan for today
        $day = (new \DateTime())->format('l');
        $mealPlan = $generatorService->generateMealPlan($user, $day);

        $this->addFlash('success', 'Your meal plan has been successfully generated!');

        return $this->redirectToRoute('app_meal_plan_show', ['id' => $mealPlan->getId()]);
    }

    #[Route('/{id}', name: 'app_meal_plan_show')]
    public function show(MealPlan $mealPlan): Response
    {
        if ($mealPlan->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('meal_plan/show.html.twig', [
            'meal_plan' => $mealPlan,
        ]);
    }

    #[Route('/{id}/pdf', name: 'app_meal_plan_pdf')]
    public function pdf(MealPlan $mealPlan, PdfGeneratorService $pdfGeneratorService): Response
    {
        if ($mealPlan->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $filePath = $pdfGeneratorService->generateMealPlanPdf($mealPlan);

        return $this->file($filePath, basename($filePath), ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }
}
