<?php

namespace App\Listeners;

use App\Events\VerifyPatientEvent;
use App\Notifications\PatientVerifyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVerifyPatientEmailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(VerifyPatientEvent $event)
    {
        //
        $this->sendOrderEmail($event->user);
    }

    function sendOrderEmail($user){
        $user->notify(new PatientVerifyNotification());
    }
}
