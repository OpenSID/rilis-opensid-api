<?php

namespace App\Providers;

use App\Models\Dokumen;
use App\Policies\DokumenPolicy;
use App\Supports\CustomUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Dokumen::class => DokumenPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Adding custom provider
        $this->app['auth']->provider('custom', function ($app, array $config) {
            return new CustomUserProvider($app['hash'], $config['model'], $config['belongsTo']);
        });

        // Authorization gate
        Gate::define('is-admin', function (Authenticatable $user) {
            return $user->id_grup == 1; // grup admin.
        });
    }
}
