<?php

namespace LPDFBuilder;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/pdf-builder.php' => config_path('pdf-builder.php'),
            ], 'config');


            $this->commands([
                //
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/pdf-builder.php', 'pdf-builder');
    }
}
