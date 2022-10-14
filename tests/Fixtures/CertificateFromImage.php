<?php

namespace LPDFBuilder\Tests\Fixtures;

use LPDFBuilder\Generation\AbstractDocumentFromImage;
use setasign\Fpdi\Fpdi;

class CertificateFromImage extends AbstractDocumentFromImage
{
    protected function generate(): Fpdi
    {
        $pdf = new Fpdi();

        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage('L', [85.6, 53]);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(3, 2);
        $pdf->Cell(79.6, 3, 'Position:', 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(1, 67, 92);
        $pdf->SetXY(3, 6);
        $pdf->Cell(79.6, 3, 'Developer', 0, 1, 'C');

        return $pdf;
    }
}
