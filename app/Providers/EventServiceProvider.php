<?php

namespace App\Providers;

use App\Events\OrderCreated;
use App\Events\OrderPaid;
use App\Events\OrderPaymentFailed;
use App\Listeners\CreateTransaction;
use App\Listeners\EmptyCart;
use App\Listeners\MarkOrderPaymentFailed;
use App\Listeners\MarkOrderProcessing;
use App\Listeners\ProcessPayment;
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
        OrderCreated::class => [
            ProcessPayment::class,
            EmptyCart::class
        ],
        OrderPaymentFailed::class => [
            MarkOrderPaymentFailed::class
        ],
        OrderPaid::class => [
            CreateTransaction::class,
            MarkOrderProcessing::class
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
