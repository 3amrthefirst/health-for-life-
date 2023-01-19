<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AppointmentModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'appointment';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'doctor_id' => 'string',
        'patient_id' => 'string',
        'appointment_slots_id' => 'string',
        'status' => 'string'
    ];

    public function doctor()
    {
        return $this->belongsTo(DoctorModel::class,'doctor_id');
    }
    public function patient()
    {
        return $this->belongsTo(PatientsModel::class, 'patient_id');
    }

}
