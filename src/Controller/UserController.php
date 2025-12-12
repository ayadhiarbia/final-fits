<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    #[Route('/dashboard', name: 'app_user_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('user/dashboard.html.twig');
    }

    #[Route('/profile', name: 'app_user_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Your profile has been updated successfully!');
            return $this->redirectToRoute('app_user_profile');
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/settings', name: 'app_user_settings')]
    public function settings(): Response
    {
        return $this->render('user/settings.html.twig');
    }

    #[Route('/meal-plans', name: 'app_user_meal_plans')]
    public function mealPlans(): Response
    {
        return $this->render('user/meal_plans.html.twig');
    }

    #[Route('/workouts', name: 'app_user_workouts')]
    public function workouts(): Response
    {
        return $this->render('user/workouts.html.twig');
    }

    #[Route('/progress', name: 'app_user_progress')]
    public function progress(): Response
    {
        return $this->render('user/progress.html.twig');
    }
}
