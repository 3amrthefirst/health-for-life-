<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Appointment_schedulModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'appointment_schedul';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'doctor_id' => 'string',
        'appointment_slots_id' => 'string',
        'status' => 'string'
    ];
}
