<?php

namespace LPDFBuilder\Tests\FromImage;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use LPDFBuilder\Tests\Fixtures\CertificateFromImage;
use LPDFBuilder\Tests\TestCase;

class CertificateGenerationTest extends TestCase
{
    /** @test */
    public function inline_return_response()
    {
        $response = (new CertificateFromImage())->inline('foo-bar');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('inline; filename="foo-bar.pdf"', $response->headers->get('content-disposition'));
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    /** @test */
    public function download_return_response()
    {
        $response = (new CertificateFromImage())->download('foo-bar');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('attachment; filename="foo-bar.pdf"', $response->headers->get('content-disposition'));
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    /** @test */
    public function create_temporal_file()
    {
        $path = (new CertificateFromImage())->temporalFile('foo-bar');

        $this->assertStringStartsWith(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'foo-bar', $path);
    }

    /** @test */
    public function save_file()
    {
        Storage::delete('foo-bar.pdf');
        $this->assertFalse(Storage::exists('foo-bar.pdf'));
        (new CertificateFromImage())->save(null, 'foo-bar', true);
        $this->assertTrue(Storage::exists('foo-bar.pdf'));
    }
}
