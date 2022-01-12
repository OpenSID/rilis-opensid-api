<?php

namespace App\Services\Surat;

use Illuminate\Support\ServiceProvider;

class SuratServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton('surat', function ($app) {
            return new SuratManager($app);
        });

        $this->app->singleton('surat.driver', function ($app) {
            return $app['surat']->driver();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return ['surat', 'surat.driver'];
    }
}
