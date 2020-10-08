<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Observers\CartObserver;
use App\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Cart::observe(CartObserver::class);
    }
}
