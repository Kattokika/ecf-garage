<?php

namespace App\Controller;

use App\Repository\AvisRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/espace-pro', name: 'app_espace_pro')]
    public function index(AvisRepository $avisRepository, MessageRepository $messageRepository): Response
    {
        $submitted = $avisRepository->getSubmittedAmount();
        $unread = $messageRepository->getUnreadAmount();

        return $this->render('admin/index.html.twig', [
            'submitted' => $submitted,
            'unread' => $unread,
        ]);
    }
}
