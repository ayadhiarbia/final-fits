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
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/progress')]
#[IsGranted('ROLE_USER')]
class ProgressController extends AbstractController
{
    #[Route('/', name: 'app_progress_index', methods: ['GET'])]
    public function index(ProgressRepository $progressRepository): Response
    {
        $user = $this->getUser();
        $progresses = $progressRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render('progress/index.html.twig', [
            'progresses' => $progresses,
        ]);
    }

    #[Route('/new', name: 'app_progress_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $progress = new Progress();
        $form = $this->createForm(ProgressType::class, $progress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $progress->setUser($user);

            // Handle photo upload
            $photoFile = $form->get('photo')->getData();

            if ($photoFile) {
                $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('progress_photos_directory'),
                        $newFilename
                    );

                    // Set the photo path relative to the public directory
                    $progress->setPhoto('uploads/progress/' . $newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'There was an error uploading your photo. Please try again.');
                    return $this->redirectToRoute('app_progress_new');
                }
            }

            $entityManager->persist($progress);
            $entityManager->flush();

            $this->addFlash('success', 'Your progress has been recorded!');

            return $this->redirectToRoute('app_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('progress/new.html.twig', [
            'progress' => $progress,
            'form' => $form->createView(),
        ]);
    }
}
