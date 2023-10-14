<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\VehiculePhoto;
use App\Form\FilterVehiculeType;
use App\Form\VehiculeType;
use App\Form\VehiculePhotoType;
use App\Form\VehiculeTypeExtended;
use App\Form\VehiculeTypeExtendedDeletePhoto;
use App\Repository\VehiculeRepository;
use App\Service\FileManager;
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
    public function index(Request $request, VehiculeRepository $vehiculeRepository): Response
    {
        $form = $this->createForm(FilterVehiculeType::class, null, [
            'action' => $this->generateUrl('app_vehicule_index'),
            'method' => 'GET',
        ]);
        $form->handleRequest($request);
        $page = $request->query->get('page', 1);
        if (!$page) {
            $page = 1;
        }
        if ($form->isSubmitted() && $form->isValid())
        {
            $paginator = $vehiculeRepository->getVehiculePaginator($form->getData());
        } else
        {
            $paginator = $vehiculeRepository->getVehiculePaginator([
                'page' => $page,
            ]);
        }
        $next = count($paginator) < VehiculeRepository::VEHICULES_PER_PAGE * $page + 1 ? 0 : $page + 1;
        return $this->render('vehicule/index_client.html.twig', [
            'vehicules' => $paginator,
            'form' => $form,
            'previous' => $page - 1,
            'next' => $next,
        ]);
    }

    #[Route('/vehicules/{slug}', name: 'app_vehicule_show', methods: ['GET'])]
    public function show(Vehicule $vehicule): Response
    {
        return $this->render('vehicule/show_client.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }

    #[Route('/espace-pro/vehicules', name: 'app_vehicule_index_pro', methods: ['GET'])]
    public function index_pro(Request $request, VehiculeRepository $vehiculeRepository): Response
    {
        $form = $this->createForm(FilterVehiculeType::class, null, [
            'action' => $this->generateUrl('app_vehicule_index'),
            'method' => 'GET',
        ]);
        $form->handleRequest($request);
        $page = $request->query->get('page', 1);
        if ($form->isSubmitted() && $form->isValid())
        {
            $paginator = $vehiculeRepository->getVehiculePaginator($form->getData());
        } else {
            $paginator = $vehiculeRepository->getVehiculePaginator([
                'page' => $page,
            ]);
        }
        $next = count($paginator) < VehiculeRepository::VEHICULES_PER_PAGE * $page + 1 ? 0 : $page + 1;
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $paginator,
            'form' => $form,
            'previous' => $page - 1,
            'next' => $next,
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

            return $this->redirectToRoute('app_vehicule_new_photos', ['slug' => $vehicule->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/new.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/espace-pro/vehicules/{slug}/photos', name: 'app_vehicule_new_photos', methods: ['GET', 'POST'])]
    public function new_photos(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager, FileManager $fileManager): Response
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
                     "error"=> "File is empty",
                 ]], Response::HTTP_BAD_REQUEST);
             }

             $pictureFilename = $fileManager->upload($pictureFile);
             $vehiculePhoto->setFilename($pictureFilename);
             $vehiculePhoto->setVehicule($vehicule);
             $entityManager->persist($vehiculePhoto);
             if (!$vehicule->getThumbnail()) {
                 $vehicule->setThumbnail($vehiculePhoto);
             }
             $entityManager->flush();

             return $this->json(['data' => [
                 $pictureFilename,
             ]]);
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
        FileManager $fileManager,
    ): Response
    {
        $form = $this->createForm(VehiculeTypeExtendedDeletePhoto::class, null, [
            'vehicule' => $vehicule,
        ]);
        $form->handleRequest($request);

        $photosToDelete = [];

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
                    $photosToDelete[] = $photo->getFilename();
                }
            }
            $entityManager->flush();
            foreach ($photosToDelete as $filename) {
                $fileManager->remove($filename);
            }

            return $this->redirectToRoute('app_vehicule_edit', ['slug' => $vehicule->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/delete_photos.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/espace-pro/vehicules/{slug}/edit', name: 'app_vehicule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VehiculeTypeExtended::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_vehicule_index_pro', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vehicule/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
        ]);
    }

    #[Route('/espace-pro/vehicules/{slug}', name: 'app_vehicule_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Vehicule $vehicule,
        EntityManagerInterface $entityManager,
        FileManager $fileManager,
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicule->getSlug(), $request->request->get('_token'))) {
            $filenames = [];
            foreach ($vehicule->getPhotos() as $photo) {
                $filenames[] = $photo->getFilename();
            }

            $vehicule->removeAllPhotos();
            $vehicule->setThumbnail(null);
            $entityManager->flush();
            # Relation cyclique entre vehicule et photos, on doit appeler flush 2 fois pour enlever les relations
            $entityManager->remove($vehicule);
            $entityManager->flush();
            $fileManager->remove($filenames);
        }
        return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }

    public function vehicules(VehiculeRepository $vehiculeRepository, ?int $max): Response
    {
        if ($max) {
            $vehicules = $vehiculeRepository->findAllWithLimit($max);
        } else {
            $vehicules = $vehiculeRepository->findAll();
        }
        return $this->render('vehicule/_vehicules.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }
}
