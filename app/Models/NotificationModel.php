<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NotificationModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'notification';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'form_user_id' => 'string',
        'doctor_id' => 'string',
        'patient_id' => 'string',
        'appointment_id' => 'string',
        'type' => 'string',
        'status' => 'string',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientsModel::class, 'form_user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(DoctorModel::class, 'form_user_id');
    }
}
