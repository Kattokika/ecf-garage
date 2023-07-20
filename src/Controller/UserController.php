<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}
    #[Route('/users', name: 'app_users')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/list-users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/{id}', name: 'app_user')]
    public function show(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');
        $form->handleRequest($request);
        # TODO: flash a message to confirm the action
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_users');
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'user_form' => $form,
        ]);
    }

    #[Route('/users/nouveau', name: 'app_new_user', priority: 2)]
    public function create(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {   $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        # TODO: flash a message to confirm the action
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $plaintextPassword = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword,
            );
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_users');
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'user_form' => $form,
        ]);
    }

    # #[Route('/conference/{id}', name: 'conference')]
    # public function show(Environment $twig, Conference $conference, CommentRepository $commentRepository): Response
    # {
    #    return new Response($twig->render('conference/show.html.twig', [
    #        'conference' => $conference,
    #        'comments' => $commentRepository->findBy(['conference' => $conference], ['createdAt' => 'DESC']),
    #    ]));
    # }
}
