<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Form\FormateurType;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class FormateurController extends AbstractController
{
    #[Route('/formateur', name: 'app_formateur')]
    public function index(Formateur $formateur = null, FormateurRepository $formateurRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $formateurs = $formateurRepository->findAll();

        $form = $this->createForm(FormateurType::class, $formateur);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $formateur = $form->getData();

            $entityManager->persist($formateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_formateur');
        }
        return $this->render('formateur/index.html.twig', [
            'formateurs' => $formateurs,
            'formAddFormateur' => $form
        ]);
    }

    #[Route('/formateur/{id}/delete', name: 'delete_formateur')]
    public function deleteFormateur(Formateur $formateur, EntityManagerInterface $em)
    {
        $em->remove($formateur);
        $em->flush();

        return $this->redirectToRoute('app_formateur');
    }
}
