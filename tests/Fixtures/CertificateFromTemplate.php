<?php

namespace LPDFBuilder\Tests\Fixtures;

use Illuminate\Support\Facades\Storage;
use LPDFBuilder\Generation\AbstractDocumentFromTemplate;
use setasign\Fpdi\Fpdi;

class CertificateFromTemplate extends AbstractDocumentFromTemplate
{
    protected ?string $sourceTemplateDisk = 'pfd_templates';
    protected ?string $sourceTemplateName = 'example.pdf';
    protected int $templatePageWidth      = 100;
    protected int $templatePageHeight     = 297;

    public static function prepareTemplateForTest()
    {
        Storage::disk('pfd_templates')
               ->put(
                   'example.pdf',
                   file_get_contents(__DIR__.'/resources/pfd_templates/example.pdf')
               );
    }

    protected function applyContent(Fpdi $pdf, int $page = 1): Fpdi
    {
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(51, 51, 51);
        $pdf->SetXY(5, 40);
        $pdf->Cell(90, 10, 'Test name', 0, 1, 'C');

        return $pdf;
    }
}
