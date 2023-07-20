<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\VehiculePhoto;
use App\Form\VehiculeType;
use App\Form\VehiculePhotoType;
use App\Form\VehiculeTypeExtended;
use App\Form\VehiculeTypeExtendedDeletePhoto;
use App\Repository\VehiculeRepository;
use App\Service\FileManager;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;

class VehiculeController extends AbstractController
{
    #[Route('/vehicules', name: 'app_vehicule_index', methods: ['GET'])]
    public function index(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehiculeRepository->findAll(),
        ]);
    }

    #[Route('/espace-pro/vehicules', name: 'app_vehicule_index_pro', methods: ['GET'])]
    public function index_pro(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehiculeRepository->findAll(),
        ]);
    }

    #[Route('/espace-pro/vehicules/nouveau', name: 'app_vehicule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vehicule);
            $entityManager->flush();

            return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/new.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/espace-pro/vehicules/{slug}/photos', name: 'app_vehicule_new_photos', methods: ['GET', 'POST'])]
    public function new_photos(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager, FileManager $fileUploader): Response
    {
        $vehiculePhoto = new VehiculePhoto();
        $vehiculePhoto->setVehicule($vehicule);
        $form = $this->createForm(VehiculePhotoType::class, $vehiculePhoto);
        $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             /** @var UploadedFile $brochureFile */
             $pictureFile = $form->get('picture')->getData();
             if (!$pictureFile) {
                 return $this->json(['data' => [
                     "empty?"
                 ]]);
             }

             $pictureFilename = $fileUploader->upload($pictureFile);
             $vehiculePhoto->setFilename($pictureFilename);
             $vehiculePhoto->setVehicule($vehicule);
             $entityManager->persist($vehiculePhoto);
             $entityManager->flush();

             return $this->json(['data' => [
                 # $form->isSubmitted(),
                 # $file,
                 "testdata, should be good?",
                 $pictureFilename,
             ]]);

             # return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
         }

        return $this->render('vehicule/new_photos.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/espace-pro/vehicules/{slug}/photos/delete', name: 'app_vehicule_delete_photos', methods: ['GET', 'POST'])]
    public function delete_photos(
        Request $request,
        Vehicule $vehicule,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $form = $this->createForm(VehiculeTypeExtendedDeletePhoto::class, null, [
            'vehicule' => $vehicule,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photos = $form->get('pictures')->getData();
            $thumbnail = $vehicule->getThumbnail();
            foreach ($photos as $photo) {
                if ($photo === $thumbnail) {
                    $form->addError(new FormError(
                        'Cette photo est la photo miniature. Veuillez d\'abord la changer.'
                    ));
                    return $this->render('vehicule/delete_photos.html.twig', [
                        'vehicule' => $vehicule,
                        'form' => $form,
                    ]);
                }

                if (true === $vehicule->getPhotos()->contains($photo)) {
                    $vehicule->removePhoto($photo);
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_vehicule_edit', ['slug' => $vehicule->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/delete_photos.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }


    #[Route('/vehicules/{slug}', name: 'app_vehicule_show', methods: ['GET'])]
    public function show(Vehicule $vehicule): Response
    {
        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }

    #[Route('/espace-pro/vehicules/{slug}', name: 'app_vehicule_show_pro', methods: ['GET'])]
    public function show_pro(Vehicule $vehicule): Response
    {
        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }

    #[Route('/espace-pro/vehicules/{slug}/edit', name: 'app_vehicule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VehiculeTypeExtended::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/espace-pro/vehicules/{slug}', name: 'app_vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vehicule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }
}
