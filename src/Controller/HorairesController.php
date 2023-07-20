<?php

namespace App\Controller;

use App\Entity\Horaires;
use App\Form\HorairesType;
use App\Form\HorairesListType;
use App\Repository\HorairesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HorairesController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/espace-pro/horaires', name: 'app_horaires_edit', methods: ['GET', 'POST'])]
    public function index2(Request $request, HorairesRepository $horairesRepository, EntityManagerInterface $entityManager): Response
    {
        $horaires = $horairesRepository->findAll();

        $form = $this->createForm(HorairesListType::class, [
            'horaires' => $horaires,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_horaires_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('horaires/edit.html.twig', [
            'list_horaires' => $horaires,
            'form' => $form,
        ]);
    }

    public function horaires(HorairesRepository $horairesRepository): Response
    {
        return $this->render('horaires/_horaires.html.twig', [
            'horaires' => $horairesRepository->findAll(),
        ]);
    }
}
