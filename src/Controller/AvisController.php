<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    #[Route('/espace-pro/avis', name: 'app_avis_index', methods: ['GET'])]
    public function index(Request $request, AvisRepository $avisRepository): Response
    {
        $form = $this->createFormBuilder(null, ['csrf_protection' => false])
            ->setAction($this->generateUrl('app_avis_index'))
            ->setMethod('GET')
            ->add('showAll', CheckboxType::class, [
                'required' => false,
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
                'label' => 'Montrer tous les avis'
            ])
            ->add('page', HiddenType::class, [
                'required'=> false,
            ])
            ->getForm();

        $form->handleRequest($request);
        $page = $request->query->get('page', 1);

        if ($form->isSubmitted() && $form->isValid())
        {
            $paginator = $avisRepository->getAvisPaginator($page, $form->getData()['showAll']);
        } else {
            $paginator = $avisRepository->getAvisPaginator($page);
        }
        $next = count($paginator) < AvisRepository::AVIS_PER_PAGE * $page + 1 ? 0 : $page + 1;

        return $this->render('avis/index.html.twig', [
            'avis' => $paginator,
            'form' => $form,
            'previous' => $page - 1,
            'next' => $next,
        ]);
    }

    #[Route('/espace-pro/avis/new', name: 'app_avis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avis = new Avis();
        $avis->setStatus("accepted");
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avis);
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/new.html.twig', [
            'avis' => $avis,
            'form' => $form,
        ]);
    }

    #[Route('/espace-pro/avis/{id}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avis): Response
    {
        return $this->render('avis/show.html.twig', [
            'avis' => $avis,
        ]);
    }

    #[Route('/espace-pro/avis/{id}/edit', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avis $avis, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvisType::class, $avis, array(
            'valider_avis' => true,
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
//            if ($avis->getStatus() == 'accepted')
//            {
//               # TODO: invalidate cache
//            }

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/edit.html.twig', [
            'avis' => $avis,
            'form' => $form,
        ]);
    }

    #[Route('/espace-pro/avis/{id}', name: 'app_avis_delete', methods: ['POST'])]
    public function delete(Request $request, Avis $avis, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avis->getId(), $request->request->get('_token'))) {
            $entityManager->remove($avis);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/donner-son-avis', name: 'app_avis_client', methods: ['GET', 'POST'])]
    public function new_client(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avis = new Avis();
        $avis->setStatus("submitted");
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avis);
            $entityManager->flush();

            return $this->redirectToRoute('app_homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/new_client.html.twig', [
            'avis' => $avis,
            'form' => $form,
        ]);
    }

    public function derniers_avis(AvisRepository $avisRepository, ?int $max = 4): Response
    {
        # TODO: ajouter valeur cachée pour la moyenne des avis validés
        # invalider le cache quand un nouvel avis est validé
        return $this->render('avis/_derniers_avis.html.twig', [
            'avis' => $avisRepository->findAllByStatus('accepted', $max),
        ]);
    }
}
