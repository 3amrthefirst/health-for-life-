<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PatientsModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'patients';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'patient_id' => 'string',
        'type' => 'string',
        'total_points' => 'string',
        'status' => 'string'
    ];

    public function insurance_company()
    {
        return $this->belongsTo(SpecialtieModel::class,'insurance_company_id');
    }

}
