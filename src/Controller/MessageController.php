<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/espace-pro/messages', name: 'app_message_index', methods: ['GET'])]
    public function index(Request $request, MessageRepository $messageRepository): Response
    {
        $form = $this->createFormBuilder(null, ['csrf_protection' => false])
            ->setAction($this->generateUrl('app_message_index'))
            ->setMethod('GET')
            ->add('showUnread', CheckboxType::class, [
                'required' => false,
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
                'label' => 'Masquer messages lus'
            ])
            ->add('page', HiddenType::class, [
                'required'=> false,
            ])
            ->getForm();

        $form->handleRequest($request);
        $page = $request->query->get('page', 1);

        if ($form->isSubmitted() && $form->isValid())
        {
            $paginator = $messageRepository->getMessagePaginator($page, $form->getData()['showUnread']);
        } else {
            $paginator = $messageRepository->getMessagePaginator($page);
        }
        $next = count($paginator) < MessageRepository::MESSAGES_PER_PAGE * $page + 1 ? 0 : $page + 1;

        return $this->render('message/index.html.twig', [
            'messages' => $paginator,
            'form' => $form,
            'previous' => $page - 1,
            'next' => $next,
        ]);
    }

    #[Route('/contact', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setLu(false);
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/espace-pro/messages/{id}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message, EntityManagerInterface $entityManager): Response
    {
        $message->setLu(true);
        $entityManager->persist($message);
        $entityManager->flush();
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }
//
//    #[Route('/espace-pro/messages/{id}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response
//    {
//        $form = $this->createForm(MessageType::class, $message);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('message/edit.html.twig', [
//            'message' => $message,
//            'form' => $form,
//        ]);
//    }

    #[Route('/espace-pro/messages/{id}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
    }
}
