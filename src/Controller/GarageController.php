<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GarageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(AvisRepository $avisRepository): Response
    {
        $noteMoyenne = $avisRepository->getAverageRate();
        return $this->render('garage/index.html.twig', [
            'note_moyenne' =>$noteMoyenne,
        ]);
    }
}
