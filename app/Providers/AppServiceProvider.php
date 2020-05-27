<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\ServiceFactory\ZipkinContext;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app('Dingo\Api\Exception\Handler')->register(function (\LogicException $exception) {
            $httpException = new \Symfony\Component\HttpKernel\Exception\HttpException(400, $exception->getMessage(), $exception, [], $exception->getCode());
            return app('Dingo\Api\Exception\Handler')->handle($httpException);
        });
        //拦截422状态码
        \API::error(function (\Dingo\Api\Exception\ValidationHttpException $exception){
            $errorMes =$exception->getErrors();
            abort(422,$errorMes->first());
        });

        \API::error(function (\Illuminate\Auth\AuthenticationException $exception){
            abort(401);
        });
        $this->app->singleton(ZipkinContext::class, function () {
            return new ZipkinContext();
        });
    }
}
