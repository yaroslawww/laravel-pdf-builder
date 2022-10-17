<?php

namespace LPDFBuilder\Generation;

trait HasTemplateWithImmutablePagesSize
{
    use HasTemplate;

    protected int $templatePageWidth  = 210;
    protected int $templatePageHeight = 297;

    public function setSize(int $templatePageWidth, int $templatePageHeight): static
    {
        $this->templatePageWidth  = $templatePageWidth;
        $this->templatePageHeight = $templatePageHeight;

        return $this;
    }

    public function setTemplatePageWidth(int $templatePageWidth): static
    {
        $this->templatePageWidth = $templatePageWidth;

        return $this;
    }

    public function setTemplatePageHeight(int $templatePageHeight): static
    {
        $this->templatePageHeight = $templatePageHeight;

        return $this;
    }
}
