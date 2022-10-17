<?php

namespace LPDFBuilder\Generation;

use setasign\Fpdi\Fpdi;

abstract class AbstractDocumentFromTemplate extends AbstractDocumentFromImage
{
    use HasTemplateWithImmutablePagesSize;

    /**
     * @inerhitDoc
     */
    public function generate(): Fpdi
    {
        $templatePath = $this->sourceFileTemplatePath();

        $pdf = new Fpdi();

        $pagesCount = $templatePath ? $pdf->setSourceFile($templatePath) : 1;

        for ($page = 1; $page <= $pagesCount; $page++) {
            $pdf->AddPage('', [$this->templatePageWidth, $this->templatePageHeight]);
            if ($templatePath) {
                $tplId = $pdf->importPage($page);
                $pdf->useTemplate($tplId, 0, 0, $this->templatePageWidth);
            }

            $pdf = $this->applyContent($pdf, $page);
        }

        return $pdf;
    }

    /**
     * @param  Fpdi  $pdf
     * @param  int  $page
     * @return Fpdi
     */
    abstract protected function applyContent(Fpdi $pdf, int $page = 1): Fpdi;
}
