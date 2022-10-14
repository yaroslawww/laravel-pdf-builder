<?php

namespace LPDFBuilder\Generation;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Knp\Snappy\Exception\FileAlreadyExistsException;
use setasign\Fpdi\Fpdi;

abstract class AbstractDocumentFromImage implements DocumentGenerator
{
    protected string $fileExtension = 'pdf';

    public function extension(string|Extension $fileExtension = 'pdf'): static
    {
        if ($fileExtension instanceof Extension) {
            $fileExtension = $fileExtension->value;
        }

        if ($fileExtension !== Extension::PDF->value) {
            throw new \Exception('Supports only pdf extension');
        }

        return $this;
    }

    public function generateName(?string $defaultName = null): string
    {
        if ($defaultName) {
            if (Str::endsWith($defaultName, ".{$this->fileExtension}")) {
                $defaultName = Str::beforeLast($defaultName, ".{$this->fileExtension}");
            }

            return $defaultName.'.'.$this->fileExtension;
        }

        return Str::random().'.'.$this->fileExtension;
    }

    public function asView(): ?\Illuminate\Contracts\View\View
    {
        return null;
    }

    public function temporalFile(?string $filename = null): ?string
    {
        $filePath = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
                    .DIRECTORY_SEPARATOR
                    .ltrim($this->generateName($filename), DIRECTORY_SEPARATOR);
        $this->generate()->Output('F', $filePath);

        return $filePath;
    }

    public function inline(?string $filename = null): Response
    {
        return new Response($this->generate()->Output('S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.Str::afterLast($this->generateName($filename), DIRECTORY_SEPARATOR).'"',
            'Cache-Control'       => 'private, max-age=0, must-revalidate',
            'Pragma'              => 'public',
        ]);
    }

    public function download(?string $filename = null): Response
    {
        return new Response($this->generate()->Output('S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.Str::afterLast($this->generateName($filename), DIRECTORY_SEPARATOR).'"',
            'Cache-Control'       => 'private, max-age=0, must-revalidate',
            'Pragma'              => 'public',
        ]);
    }

    public function save(?string $disc = null, ?string $filename = null, bool $overwrite = false, array $options = []): static
    {
        $filename = $this->generateName($filename);
        $storage  = Storage::disk($disc);
        if (!$overwrite && $storage->exists($filename)) {
            // tangled with snappy, maybe good to create internal own exception?
            throw new FileAlreadyExistsException(\sprintf('The output file \'%s\' already exists.', $storage->path($filename)));
        }

        $storage->put($filename, $this->generate()->Output('S'));

        return $this;
    }

    abstract protected function generate(): Fpdi;
}
