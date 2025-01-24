<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(SessionRepository $sessionRepository): Response
    {
        $sessions = $sessionRepository->findAll();
        $finishedSessions = $sessionRepository->getFinishedSessions();
        $ongoingSessions = $sessionRepository->getOngoingSessions();        
        $incomingSessions = $sessionRepository->getIncomingSessions();        
        
        
        return $this->render('home/index.html.twig', [
            'sessions' => $sessions,
            'finishedSessions' => $finishedSessions,
            'ongoingSessions' => $ongoingSessions,
            'incomingSessions' => $incomingSessions
        ]);
    }
}
