<?php

namespace LPDFBuilder\Generation;

use Illuminate\Support\Facades\Storage;

trait HasTemplate
{
    protected ?string $sourceTemplateDisk = null;
    protected ?string $sourceTemplateName = null;

    /**
     * Specify template to use.
     *
     * @param string|null $sourceTemplateName
     * @return $this
     */
    public function setTemplate(?string $sourceTemplateName = null): static
    {
        $this->sourceTemplateName = $sourceTemplateName;

        return $this;
    }

    /**
     * Specify template disk to use.
     *
     * @param string|null $sourceTemplateDisk
     * @return $this
     */
    public function setTemplateDisk(?string $sourceTemplateDisk = null): static
    {
        $this->sourceTemplateDisk = $sourceTemplateDisk;

        return $this;
    }

    /**
     * Get template storage disk.
     *
     * @return string|null
     */
    protected function sourceTemplateDisk(): ?string
    {
        return $this->sourceTemplateDisk?:config('pdf-builder.templates-storage');
    }

    /**
     * Get path to file template.
     *
     * @return string|null
     */
    protected function sourceFileTemplatePath(): ?string
    {
        return $this->sourceTemplateName?Storage::disk($this->sourceTemplateDisk)->path($this->sourceTemplateName):null;
    }
}
