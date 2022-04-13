<?php

namespace App\Providers;

use App\Cart\Cart;
use App\Http\Middleware\Cart\Payment\Gateway;
use App\Http\Middleware\Cart\Payment\Gateways\StripeGateway;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Stripe\Stripe;
use Stripe\StripeClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Cart::class, function ($app) {
            return new Cart($app->auth->user());
        });
        $this->app->singleton(Gateway::class, StripeGateway::class);
        $this->app->singleton(StripeClient::class, function (){
            return new StripeClient(config('services.stripe.secret'));
        });
        Cashier::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
}
