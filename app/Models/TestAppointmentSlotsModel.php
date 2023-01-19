<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TestAppointmentSlotsModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'test_appointment_slots';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'status' => 'string'
    ];
}
