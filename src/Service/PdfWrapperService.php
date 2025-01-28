<?php

// src/Service/PdfWrapperService.php

namespace App\Service;

use Nucleos\DompdfBundle\Wrapper\DompdfWrapperInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfWrapperService
{
    private DompdfWrapperInterface $wrapper;

    public function __construct(DompdfWrapperInterface $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    public function streamPdf(string $html, string $filename): StreamedResponse
    {
        // Retourner une réponse pour streamer le PDF
        return $this->wrapper->getStreamResponse($html, $filename);
    }

    public function getPdfContent(string $html): string
    {
        // Retourner le contenu binaire du PDF
        return $this->wrapper->getPdf($html);
    }
}
