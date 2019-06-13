<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\LoginEvent' => [
            'App\Listeners\LoginEventListener',
        ],
        'App\Events\OrderSendEvent' => [
            'App\Listeners\OrderSendEventListener',
        ],
        'App\Events\UnifiedOrderEvent' => [
            'App\Listeners\UnifiedOrderEventListener',
        ],
        'App\Events\MessageEvent' => [
            'App\Listeners\MessageEventListener',
        ],
        'App\Events\AssetsEvent' => [
            'App\Listeners\AssetsEventListener',
        ],
        'App\Events\ActivityMessageEvent' => [
            'App\Listeners\ActivityMessageEventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
