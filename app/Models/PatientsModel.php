<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class PatientsModel  extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'patients';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'patient_id' => 'string',
        'type' => 'string',
        'total_points' => 'string',
        'status' => 'string',
        'email_verified_at' => 'datetime'
    ];

    public function insurance_company()
    {
        return $this->belongsTo(SpecialtieModel::class,'insurance_company_id');
    }

}
