<?php

namespace App\Providers;

use App\Adpaters\Implementation\HISMS;
use App\Adpaters\Implementation\MoyasarPaymemt;
use App\Adpaters\Implementation\TestSms;
use App\Adpaters\IPayment;
use App\Adpaters\ISMSGateway;
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

        //laravel container
        //Ioc container

        //dependency manager
        $this->app->bind(IPayment::class, MoyasarPaymemt::class);

        if(env("APP_ENV")=="local"){
            //mocking, faking
            $this->app->bind(ISMSGateway::class, TestSms::class);
        }
        else{
            $this->app->bind(ISMSGateway::class, HISMS::class);
        }

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
    }
}
