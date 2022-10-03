# Laravel pdf builder.

![Packagist License](https://img.shields.io/packagist/l/yaroslawww/laravel-pdf-builder?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/yaroslawww/laravel-pdf-builder)](https://packagist.org/packages/yaroslawww/laravel-pdf-builder)
[![Total Downloads](https://img.shields.io/packagist/dt/yaroslawww/laravel-pdf-builder)](https://packagist.org/packages/yaroslawww/laravel-pdf-builder)
[![Build Status](https://scrutinizer-ci.com/g/yaroslawww/laravel-pdf-builder/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-pdf-builder/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/yaroslawww/laravel-pdf-builder/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-pdf-builder/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yaroslawww/laravel-pdf-builder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-pdf-builder/?branch=master)

Wrapper for `snappy` and `fpdi` pdf generators. Allow quickly create different templates in laravel for MVP or
production.

## Installation

Install the package via composer:

```bash
composer require yaroslawww/laravel-pdf-builder
```

Optionally you can publish the config file with:

```bash
php artisan vendor:publish --provider="LPDFBuilder\ServiceProvider" --tag="config"
```

## Usage

### Using snappy html builder

```php
<?php

use LPDFBuilder\Generation\AbstractDocumentFromHtml;

class UserCertificate extends AbstractDocumentFromHtml
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function viewName(): string
    {
        return 'certificates.user.body';
    }

    public function headerViewName(): ?string
    {
        return 'certificates.layout.header';
    }

    public function footerViewName(): ?string
    {
        return 'certificates.layout.footer';
    }

    public function viewData(): array
    {
        return [ 'user' => $this->user, ];
    }
}
```

### Using fpdi builder

```php
<?php

use LPDFBuilder\Generation\AbstractDocumentFromImage;

class UserCertificate extends AbstractDocumentFromImage
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    protected function generate(): Fpdi
    {
        $pdf = new Fpdi();
        $pdf = $this->design2($pdf, 'L');

        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage('L', [85.6, 53]);

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(3, 2);
        $pdf->Cell(79.6, 3, 'Name:', 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(1, 67, 92);
        $pdf->SetXY(3, 6);
        $pdf->Cell(79.6, 3, Str::limit($this->user->name, 30, ''), 0, 1, 'C');

        return $pdf;
    }
}
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/) 
