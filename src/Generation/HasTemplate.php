<?php

namespace LPDFBuilder\Generation;

use Illuminate\Support\Facades\Storage;

trait HasTemplate
{
    protected ?string $sourceTemplateDisk = null;
    protected ?string $sourceTemplateName = null;

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
