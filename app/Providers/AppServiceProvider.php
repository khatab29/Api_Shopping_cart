<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Billing\PaymentStrategyInterface;
use App\Billing\PayAtStorePaymentStrategy;
use App\Billing\CashOnDeliveryPaymentStrategy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * -
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PaymentStrategyInterface::class, function ($app) {

            if (request()->input('shipment_type') !== '1') {
                return new PayAtStorePaymentStrategy();
            }
                return new CashOnDeliveryPaymentStrategy();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
