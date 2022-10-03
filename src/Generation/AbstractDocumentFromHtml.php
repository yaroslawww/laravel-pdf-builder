<?php

namespace LPDFBuilder\Generation;

use Barryvdh\Snappy\Facades\SnappyImage;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

abstract class AbstractDocumentFromHtml implements DocumentGenerator
{
    protected string $fileExtension = 'pdf';

    protected mixed $certificateData = [];

    /**
     * List of possible extensions.
     *
     * @return array
     */
    public function possibleExtensions(): array
    {
        return [Extension::PDF->value, Extension::JPG->value];
    }

    /**
     * @inheritDoc
     */
    public function extension(string $fileExtension = 'pdf'): static
    {
        if (!in_array($fileExtension, $this->possibleExtensions())) {
            throw new \Exception('Not valid extension. Allowed: '.implode(', ', $this->possibleExtensions()));
        }
        $this->fileExtension = $fileExtension;

        return $this;
    }


    abstract public function viewName(): string;

    public function headerViewName(): ?string
    {
        return null;
    }

    public function footerViewName(): ?string
    {
        return null;
    }

    public function viewData(): array
    {
        return [
            'certificate' => $this->certificateData,
        ];
    }

    /**
     * @inheritDoc
     */
    public function asView(): ?\Illuminate\Contracts\View\View
    {
        return View::make($this->viewName(), $this->viewData());
    }

    /**
     * @return \Barryvdh\Snappy\PdfWrapper|\Barryvdh\Snappy\ImageWrapper
     */
    public function file(): \Barryvdh\Snappy\PdfWrapper|\Barryvdh\Snappy\ImageWrapper
    {
        $file = match ($this->fileExtension) {
            Extension::JPG->value => SnappyImage::loadView($this->viewName(), $this->viewData()),
            default               => SnappyPdf::loadView($this->viewName(), $this->viewData()),
        };

        if ($headerViewName = $this->headerViewName()) {
            $file->setOption('header-html', view($headerViewName, $this->viewData())->render());
        }
        if ($footerViewName = $this->footerViewName()) {
            $file->setOption('footer-html', view($footerViewName, $this->viewData())->render());
        }

        return $file;
    }

    /**
     * @inheritDoc
     */
    public function temporalFile(?string $name = null): ?string
    {
        $filePath = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
                    .DIRECTORY_SEPARATOR
                    .ltrim(($name ?: $this->generateName()), DIRECTORY_SEPARATOR);
        $this->file()->save($filePath, true);

        return $filePath;
    }

    /**
     * @inheritDoc
     */
    public function inline(string $name = 'certificate'): Response
    {
        return $this->file()->inline("{$name}.{$this->fileExtension}");
    }

    /**
     * @inheritDoc
     */
    public function download(string $name = 'certificate'): Response
    {
        return $this->file()->download("{$name}.{$this->fileExtension}");
    }

    /**
     * @inheritDoc
     */
    public function save(?string $disc = null, ?string $filename = null, $overwrite = false, array $options = []): static
    {
        $storage = Storage::disk($disc);
        $this->file()->save($storage->path($filename ?: $this->generateName()), $overwrite);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function generateName(): string
    {
        return "{$this->certificateData?->uuid}.{$this->fileExtension}";
    }
}
