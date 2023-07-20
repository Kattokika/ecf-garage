<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\PasswordGenerator;
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
    public function create(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        PasswordGenerator $passwordGenerator,
    ): Response
    {   $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        # TODO: flash a message to confirm the action
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $plaintextPassword = $passwordGenerator->generate_password();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword,
            );
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->render('user/user-succes.html.twig', [
                'user' => $user,
                'plaintext_password' => $plaintextPassword,
            ]);
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'user_form' => $form,
        ]);
    }
}
