<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\RouteRegistrar;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes(function (RouteRegistrar $router) {
            $router->forAccessTokens();
            $router->forTransientTokens();
        }); // 只保留获取token和刷新token接口

        Passport::tokensExpireIn(Carbon::now()->addDays(15)); //默认 token 生效期 15 天

        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30)); // token 续租 30 天
    }
}
