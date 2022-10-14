<?php

namespace LPDFBuilder\Tests\Fixtures;

use LPDFBuilder\Generation\AbstractDocumentFromHtml;

class CertificateFromHtml extends AbstractDocumentFromHtml
{
    public function __construct(array $certificateData = [])
    {
        $this->certificateData = $certificateData;
    }

    public function viewName(): string
    {
        return 'certificate.body';
    }

    public function headerViewName(): ?string
    {
        return 'certificate.header';
    }

    public function footerViewName(): ?string
    {
        return 'certificate.footer';
    }
}
