<?php

namespace App\Providers;

use App\Helpers\Response;
use App\Helpers\Utils;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        $this->app->singleton('utils', function(){
            return new Utils();
        });

        $this->app->singleton('stripe', function(){
            return ( new Cashier() )->stripe();
        });

        Cashier::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
    }
}
