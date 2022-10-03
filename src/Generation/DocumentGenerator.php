<?php

namespace LPDFBuilder\Generation;

use Illuminate\Http\Response;

interface DocumentGenerator
{
    /**
     * Set extension, if builder allow different extensions.
     */
    public function extension(string $fileExtension = 'pdf'): static;

    /**
     * Return generated file name.
     */
    public function generateName(): string;

    /**
     * Return a response with the certificate as laravel view
     */
    public function asView(): ?\Illuminate\Contracts\View\View;

    /**
     * Return a temporal file path.
     */
    public function temporalFile(?string $name = null): ?string;

    /**
     * Return a response with the certificate to show in the browser.
     */
    public function inline(string $name = 'certificate'): Response;

    /**
     * Make the certificate downloadable by the user.
     */
    public function download(string $name = 'certificate'): Response;

    /**
     * Save File In filesystem.
     */
    public function save(?string $disc = null, ?string $filename = null, bool $overwrite = false, array $options = []): static;
}
