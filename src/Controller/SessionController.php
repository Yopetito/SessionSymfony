<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Form\StagiaireSessionType;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SessionController extends AbstractController
{
    #[Route('/formation/{id}/sessions', name: 'show_sessions')]
    public function index(int $id, SessionRepository $sessionRepository, Request $request, EntityManagerInterface $entityManager ): Response
    {
        $sessions = $sessionRepository->findBy(['formation' => $id]);
        
        // Formulaire ajout staigiare a la session
        $form = $this->createForm(StagiaireSessionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $stagiaire = $data['stagiaire'];
            $session = $data['session'];

            $session->addStagiaire($stagiaire);

            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('show_sessions', ['id' => $id]);
        }

        // Retour des informations a la vue
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions,
            'formAddStagiaireSession' => $form
        ]);
    } 

    #[Route('/session/{id}/details', name: 'detail_session')]
    public function detailSession(Session $session, SessionRepository $sessionRepository): Response
    {
        $nonInscrits = $sessionRepository->findNonInscrits($session->getId());
        $nonProgrammes = $sessionRepository->findNonProgramme($session->getId());
        
        return $this->render('session/detailsession.html.twig', [
            'session' => $session,
            'nonInscrits' => $nonInscrits,
            'nonProgrammes' => $nonProgrammes
        ]);
    }
    
    #[Route('/session/add', name: 'add_session')]
    public function addSession(Session $session = null, SessionRepository $sessionRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $session = $form->getData();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('app_formation');
        }

        return $this->render('session/addsession.html.twig', [
            'formAddSession' => $form
        ]);
    }
}
