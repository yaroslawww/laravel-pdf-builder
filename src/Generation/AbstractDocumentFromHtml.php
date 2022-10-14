<?php

namespace LPDFBuilder\Generation;

use Barryvdh\Snappy\Facades\SnappyImage;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

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
    public function extension(string|Extension $fileExtension = 'pdf'): static
    {
        if ($fileExtension instanceof Extension) {
            $fileExtension = $fileExtension->value;
        }
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

        try {
            if ($headerViewName = $this->headerViewName()) {
                $file->setOption('header-html', view($headerViewName, $this->viewData())->render());
            }
            if ($footerViewName = $this->footerViewName()) {
                $file->setOption('footer-html', view($footerViewName, $this->viewData())->render());
            }
        } catch (\InvalidArgumentException) {
        }

        return $file;
    }

    /**
     * @inheritDoc
     */
    public function temporalFile(?string $filename = null): ?string
    {
        $filePath = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
                    .DIRECTORY_SEPARATOR
                    .ltrim($this->generateName($filename), DIRECTORY_SEPARATOR);
        $this->file()->save($filePath, true);

        return $filePath;
    }

    /**
     * @inheritDoc
     */
    public function inline(?string $filename = null): Response
    {
        return $this->file()->inline(Str::afterLast($this->generateName($filename), DIRECTORY_SEPARATOR));
    }

    /**
     * @inheritDoc
     */
    public function download(?string $filename = null): Response
    {
        return $this->file()->download(Str::afterLast($this->generateName($filename), DIRECTORY_SEPARATOR));
    }

    /**
     * @inheritDoc
     */
    public function save(?string $disc = null, ?string $filename = null, $overwrite = false, array $options = []): static
    {
        $storage = Storage::disk($disc);
        $this->file()->save($storage->path($this->generateName($filename)), $overwrite);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function generateName(?string $defaultName = null): string
    {
        if ($defaultName) {
            if (Str::endsWith($defaultName, ".{$this->fileExtension}")) {
                $defaultName = Str::beforeLast($defaultName, ".{$this->fileExtension}");
            }

            return $defaultName.'.'.$this->fileExtension;
        }

        return Arr::get($this->certificateData, 'uuid', Arr::get($this->certificateData, 'id', Str::random())).".{$this->fileExtension}";
    }
}
