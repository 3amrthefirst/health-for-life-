<?php

namespace App\Listeners;

use App\Events\VerifyDoctorEvent;
use App\Notifications\DoctorVerifyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendVerifyDoctorEmailListener
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
    public function handle(VerifyDoctorEvent $event)
    {
        //
        $this->sendOrderEmail($event->user);
    }

    function sendOrderEmail($user){
        $user->notify(new DoctorVerifyNotification());
    }
}
