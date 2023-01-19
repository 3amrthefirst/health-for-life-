<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Appointment_slotsModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'appointment_slots';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'doctor_id' => 'string',
        'status' => 'string'
    ];

}
