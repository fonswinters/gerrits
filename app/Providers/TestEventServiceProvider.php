<?php

namespace App\Providers;

use App\Listeners\MessageToSession;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class TestEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MessageSending::class => [
            MessageToSession::class
        ],
        MessageSent::class => [

        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}