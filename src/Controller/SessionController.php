<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Programme;
use App\Form\SessionType;
use App\Form\ModuleInSessionType;
use App\Form\StagiaireSessionType;
use App\Repository\ModuleRepository;
use App\Repository\SessionRepository;
use App\Repository\ProgrammeRepository;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SessionController extends AbstractController
{

    //================Sessions dans une formation

    #[Route('/formation/{id}/sessions', name: 'show_sessions')]
    public function index(SessionRepository $sessionRepository, int $id): Response
    {
        $sessions = $sessionRepository->findBy(['formation' => $id]);
        
        // Retour des informations a la vue
        return $this->render('session/index.html.twig', [
            'sessions' => $sessions
        ]);
    } 

    //==============Details d'une session

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

    //==================Ajout et supression d'un stagiaire dans une session

    #[Route('/session/{id}/details/add/{stagiaireId}', name: 'addStagToSession')]
    public function AddStagToSession(int $id, int $stagiaireId, SessionRepository $sessionRepo, StagiaireRepository $stagiaireRepo, EntityManagerInterface $em)
    {
        $stagiaire = $stagiaireRepo->find($stagiaireId);
        $session = $sessionRepo->find($id);
        
        $session->addStagiaire($stagiaire);
        $em->persist($session);
        $em->flush();

        return $this->redirectToRoute('detail_session', ['id' => $id]);
    }
    
    #[Route('/session/{id}/details/remove/{stagiaireId}', name: 'removeStagToSession')]
    public function removeStagToSession(int $id, int $stagiaireId, SessionRepository $sessionRepo, StagiaireRepository $stagiaireRepo, EntityManagerInterface $em)
    {
        $stagiaire = $stagiaireRepo->find($stagiaireId);
        $session = $sessionRepo->find($id);
        
        $session->removeStagiaire($stagiaire);
        $em->persist($session);
        $em->flush();

        return $this->redirectToRoute('detail_session', ['id' => $id]);
    }
    
    //==================Ajout et supression d'un module dans une session

    #[Route('/session/{id}/details/addmodule/{moduleId}', name: 'addModuleToSession')]
    public function addModuleToSession(int $id, int $moduleId, ModuleRepository $moduleRepo, SessionRepository $sessionRepo, EntityManagerInterface $em, Request $request)
    {
        $session = $sessionRepo->find($id);
        $module = $moduleRepo->find($moduleId);
        
        $nbJour = $request->request->get('nbJour');
        $programme = new Programme();
        $programme->setModule($module);
        $programme->setSession($session);
        $programme->setNbJour($nbJour);
        
         

        $em->persist($programme);
        $em->flush();

        return $this->redirectToRoute('detail_session', ['id' => $id]);
    }
    

    #[Route('/session/{id}/details/removemodule/{programmeId}', name: 'removeModuleToSession')]
    public function removeModuleToSession(int $id, int $programmeId, ProgrammeRepository $programmeRepo, SessionRepository $sessionRepo, EntityManagerInterface $em)
    {
        $session = $sessionRepo->find($id);
        $programme = $programmeRepo->find($programmeId);
        
        $em->remove($programme);
        $em->flush();

        return $this->redirectToRoute('detail_session', ['id' => $id]);
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
