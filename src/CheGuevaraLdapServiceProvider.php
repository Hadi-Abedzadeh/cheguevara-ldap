<?php

namespace Hadiabedzadeh\CheGuevaraLdap;

use Illuminate\Support\ServiceProvider;

class SsoLoginServiceProvider extends ServiceProvider
{

    public function boot()
    {
        //$this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        // $this->loadMigrationsFrom(__DIR__ . '../database/migrations');

        if ($this->app->runningInConsole()) {
            // Publishing the configuration file.
            $this->definePublishable();
        }

    }

    public function register()
    {
    }

    private function definePublishable()
    {
        // $this->publishes([
            // realpath(__DIR__ . '/../database/migrations') => database_path('migrations'),
        // ], 'migrations');
    }
}

