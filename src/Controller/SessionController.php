<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SessionController extends AbstractController
{
    #[Route('/formation/{id}/sessions', name: 'show_sessions')]
    public function index(int $id, SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findBy(['formation' => $id]);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions
        ]);
    } 

    #[Route('/session/{id}/details', name: 'detail_session')]
    public function detailSession(SessionRepository $sessionRepo, int $id): Response
    {
        
        $session = $sessionRepo->find($id);
        $stagiaires = $session->getStagiaires();
        return $this->render('session/detailsession.html.twig', [
            'session' => $session,
            'stagiaires' => $stagiaires
        ]);
    }
}
