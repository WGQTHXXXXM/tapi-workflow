<?php

namespace App\Providers;

use App\Auth\UserGuard;
use App\Auth\UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('auth_user', function($app, $config) {
            return new UserProvider($config);
        });

        // auth:api -> token guard.
        // @throw \Exception
        Auth::extend('singulato_token', function($app, $name, array $config) {
            if ($name === 'api') {
                return app()->make(UserGuard::class, [
                    'provider' => Auth::createUserProvider($config['provider']),
                    'request'  => $app->request,
                ]);
            }
            throw new \Exception('This guard only serves "auth:api".');
        });
    }
}
