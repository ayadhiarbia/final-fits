<?php

namespace App\Controller;

use App\Entity\Progress;
use App\Repository\ProgressRepository;
use App\Form\ProgressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/progress')]
#[IsGranted('ROLE_USER')]
class ProgressController extends AbstractController
{
    #[Route('/', name: 'app_progress_index')]
    public function index(ProgressRepository $progressRepository): Response
    {
        $user = $this->getUser();
        $progresses = $progressRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render('progress/index.html.twig', [
            'progresses' => $progresses,
        ]);
    }

    #[Route('/new', name: 'app_progress_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $progress = new Progress();
        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $progress->setUser($user);

            // Handle photo upload logic here (omitted for brevity, would require file upload service)
            // For now, we'll just save the text data.

            $entityManager->persist($progress);
            $entityManager->flush();

            $this->addFlash('success', 'Your progress has been recorded!');

            return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('progress/new.html.twig', [
            'progress' => $progress,
            'Form' => $form,
        ]);
    }
}


