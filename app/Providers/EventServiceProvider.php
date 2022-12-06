<?php

namespace App\Providers;

use App\Events\ChargeWallet;
use App\Events\CreateAd;
use App\Events\DecreaseWallet;
use App\Events\UpdateFinancialRequest;
use App\Listeners\RunAfterChargeWallet;
use App\Listeners\RunAfterCreateAd;
use App\Listeners\RunAfterDecreaseWallet;
use App\Listeners\RunAfterUpdateFinancialRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        CreateAd::class => [
            RunAfterCreateAd::class,
        ],
        UpdateFinancialRequest::class => [
            RunAfterUpdateFinancialRequest::class,
        ],
        ChargeWallet::class => [
            RunAfterChargeWallet::class,
        ],
        DecreaseWallet::class => [
            RunAfterDecreaseWallet::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
