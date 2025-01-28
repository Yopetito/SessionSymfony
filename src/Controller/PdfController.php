<?php

// src/Controller/PdfController.php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Service\PdfFactoryService;
use App\Service\PdfWrapperService;
use App\Repository\StagiaireRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfController extends AbstractController
{
    #[Route('/factory-pdf/{id}', name: 'factory_pdf')]
    public function factoryPdf($id, Stagiaire $stagiaire, PdfFactoryService $pdfFactoryService, StagiaireRepository $sr): Response
    {
        $stagiaireSession = $stagiaire->getSessions();
        $stagiaireInfo = $sr->find($id);
        // Préparer le HTML
        $html = $this->renderView('pdf/template.html.twig', [
            'title' => 'PDF via DompdfFactory',
            's' => $stagiaireInfo,
            'stagiairesession' => $stagiaireSession,
        ]);

        // Générer le PDF
        $pdfContent = $pdfFactoryService->generatePdf($html);

        // Retourner une réponse avec le PDF
        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="factory_example.pdf"',
        ]);
    }

    #[Route('/wrapper-pdf', name: 'wrapper_pdf')]
    public function wrapperPdf(PdfWrapperService $pdfWrapperService): Response
    {
        // Préparer le HTML
        $html = $this->renderView('pdf/template.html.twig', [
            'title' => 'PDF via DompdfWrapper',
            'content' => 'Ceci est un exemple généré avec DompdfWrapperInterface.',
        ]);

        // Streamer le PDF
        return $pdfWrapperService->streamPdf($html, 'wrapper_example.pdf');
    }

}
