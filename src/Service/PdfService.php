<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private $domPdf;

    public function __construct() {
        $this->domPdf = new Dompdf();

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        $this->domPdf->setOptions($pdfOptions);
    }

    public function showPdf($html): void
    {
        $this->domPdf->loadHtml($html);
        $this->domPdf->setPaper('A4');
        $this->domPdf->render();
        $this->domPdf->stream("recipe.pdf", [
            "Attachment" => false
        ]);
    }

    public function generateBinaryPDF($html): string
    {
        $this->domPdf->loadHtml($html);
        $this->domPdf->setPaper('A4');
        $this->domPdf->render();
        return $this->domPdf->output();
    }
}