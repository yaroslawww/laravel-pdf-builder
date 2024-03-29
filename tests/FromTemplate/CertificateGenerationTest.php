<?php

namespace LPDFBuilder\Tests\FromTemplate;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use LPDFBuilder\Tests\Fixtures\CertificateFromTemplate;
use LPDFBuilder\Tests\TestCase;

class CertificateGenerationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        CertificateFromTemplate::prepareTemplateForTest();
    }


    /** @test */
    public function inline_return_response()
    {
        $response = (new CertificateFromTemplate())->inline('foo-bar');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('inline; filename="foo-bar.pdf"', $response->headers->get('content-disposition'));
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    /** @test */
    public function download_return_response()
    {
        $response = (new CertificateFromTemplate())->download('foo-bar');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('attachment; filename="foo-bar.pdf"', $response->headers->get('content-disposition'));
        $this->assertEquals('application/pdf', $response->headers->get('content-type'));
    }

    /** @test */
    public function create_temporal_file()
    {
        $path = (new CertificateFromTemplate())->temporalFile('foo-bar');

        $this->assertStringStartsWith(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'foo-bar', $path);
    }

    /** @test */
    public function save_file()
    {
        Storage::delete('foo-bar.pdf');
        $this->assertFalse(Storage::exists('foo-bar.pdf'));
        (new CertificateFromTemplate())->save(null, 'foo-bar', true);
        $this->assertTrue(Storage::exists('foo-bar.pdf'));
    }

    /** @test */
    public function change_template_name()
    {
        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessageMatches('/^.*pfd_templates\/example-foo.pdf\): Failed to open stream: No such file or directory$/');
        (new CertificateFromTemplate())
            ->setTemplate('example-foo.pdf')
            ->save(null, 'foo-bar', true);
    }

    /** @test */
    public function change_template_disk()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Disk [new_test] does not have a configured driver.');
        (new CertificateFromTemplate())
            ->setTemplateDisk('new_test')
            ->save(null, 'foo-bar', true);
    }
}
