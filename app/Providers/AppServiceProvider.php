<?php

namespace App\Providers;

use App\Extensions\ClientUserProvider;
use App\Services\ClientService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ClientService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        Http::macro('qClient', function () {
            return Http::baseUrl('https://symfony-skeleton.q-tests.com');
        });

        Http::macro('qClientWithToken', function () {
            return Http::qClient()->withHeaders([
                'Authorization' => 'Bearer '.auth()->user()->access_token,
                'Content-Type'  => 'application/json',
            ]);
        });

        Auth::provider('client', function ($app, array $config) {
            return app(ClientUserProvider::class);
        });
    }
}
