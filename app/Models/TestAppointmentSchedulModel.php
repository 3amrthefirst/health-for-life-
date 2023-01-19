<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TestAppointmentSchedulModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'test_appointment_schedul';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'test_appointment_slots_id' => 'string',
        'status' => 'string'
    ];

}
