<?php

namespace Yosinan\Chapavel;

use Illuminate\Support\ServiceProvider;

class ChapavelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/chapa.php' =>$this->app->configPath('chapa.php'),
        ], 'chapa-config');
    }
}