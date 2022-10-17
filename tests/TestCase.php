<?php

namespace LPDFBuilder\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \LPDFBuilder\ServiceProvider::class,
            \LPDFBuilder\Tests\Fixtures\TestServiceProvider::class,
            \Barryvdh\Snappy\ServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('filesystems.disks.pfd_templates', [
            'driver' => 'local',
            'root'   => storage_path('app/pfd_templates'),
        ]);

        // $app['config']->set('pdf-builder.some_key', 'some_value');
    }
}
