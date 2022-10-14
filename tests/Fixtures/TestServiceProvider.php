<?php

namespace LPDFBuilder\Tests\Fixtures;

use Illuminate\View\FileViewFinder;

class TestServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind('view.finder', function ($app) {
            return new FileViewFinder($app['files'], array_merge($app['config']['view.paths'], [
                __DIR__.'/resources/views',
            ]));
        });
    }
}
