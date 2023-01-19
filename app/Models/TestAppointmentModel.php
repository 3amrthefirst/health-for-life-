<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TestAppointmentModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'test_appointment';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'patient_id' => 'string',
        'test_appointment_slots_id' => 'string',
        'status' => 'string'
    ];

    public function patients()
    {
        return $this->belongsTo(PatientsModel::class,'patient_id');
    }

}
