<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class StagiaireController extends AbstractController
{
    #[Route('/stagiaire', name: 'app_stagiaire')]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {
        $stagiaires = $stagiaireRepository->findAll();

        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires
        ]);
    }

    #[Route('/stagiaire/add', name: 'app_addstagiaire')]
    public function addStagiaire(Request $request, EntityManagerInterface $entityManager): Response
    {   
        $stagiaire = new Stagiaire;
        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stagiaire = $form->getData();

            $entityManager->persist($stagiaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire');
        }
        
        return $this->render('stagiaire/newstagiaire.html.twig', [
            'formAddStagiaire' => $form,
        ]);
    }


    #[Route('/stagiaire/{id}', name: 'app_detailstagiaire')]
    public function show(Stagiaire $stagiaire): Response
    {
            return $this->render('stagiaire/detailstagiaire.html.twig', [
            'stagiaire' => $stagiaire
        ]);
    }




}
