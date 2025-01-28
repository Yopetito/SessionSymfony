<?php
// src/Service/PdfFactoryService.php

namespace App\Service;

use Nucleos\DompdfBundle\Factory\DompdfFactoryInterface;
use Dompdf\Dompdf;

class PdfFactoryService
{
    private DompdfFactoryInterface $factory;

    public function __construct(DompdfFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function generatePdf(string $html, array $options = []): string
    {
        // Créer une instance de Dompdf
        /** @var Dompdf $dompdf */
        $dompdf = $this->factory->create($options);

        // Charger le HTML et générer le PDF
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Retourner le contenu binaire du PDF
        return $dompdf->output();
    }
}
