<?php

namespace App\Providers;

use App\Events\VerifyDoctorEvent;
use App\Events\VerifyPatientEvent;
use App\Listeners\SendVerifyDoctorEmailListener;
use App\Listeners\SendVerifyPatientEmailListener;
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
        ]
        , VerifyDoctorEvent::class => [
            SendVerifyDoctorEmailListener::class,
        ]
        , VerifyPatientEvent::class => [
            SendVerifyPatientEmailListener::class,
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
