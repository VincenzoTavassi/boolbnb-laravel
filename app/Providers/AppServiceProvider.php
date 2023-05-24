<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

use App\Providers\Braintree_Configuration;

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
        Paginator::useBootstrap();
        $gateway = new \Braintree\Gateway([
            'environment' => 'sandbox',
            'merchantId' => 'use_your_merchant_id',
            'publicKey' => 'use_your_public_key',
            'privateKey' => 'use_your_private_key'
        ]);
    }
}
