<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SessionController extends AbstractController
{
    #[Route('/session/{id}', name: 'show_sessions')]
    public function index(int $id, SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findBy(['formation' => $id]);
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions
        ]);
    }



    // #[Route('/session/{id}', name: 'show_sessions')]
    // public function showSessions(int $id, SessionRepository $sessionRepository): Response
    // {
    //     $sessions = $sessionRepository->findBy($id);
    //     return $this->render('session/index.html.twig', [
    //         'sessions' => $sessions
    //     ]);
    // }

}
