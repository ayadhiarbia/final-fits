<?php

namespace App\Controller;

use App\Entity\Workout;
use App\Repository\WorkoutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/workout')]
#[IsGranted('ROLE_USER')]
class WorkoutController extends AbstractController
{
    #[Route('/', name: 'app_workout_index')]
    public function index(WorkoutRepository $workoutRepository): Response
    {
        // In a real app, you would filter by user or have a global set of workouts
        $workouts = $workoutRepository->findAll();

        return $this->render('workout/index.html.twig', [
            'workouts' => $workouts,
        ]);
    }

    #[Route('/{id}', name: 'app_workout_show')]
    public function show(Workout $workout): Response
    {
        return $this->render('workout/show.html.twig', [
            'workout' => $workout,
        ]);
    }
}
