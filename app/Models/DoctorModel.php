<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class DoctorModel extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable,HasApiTokens;

    protected $table = 'doctors';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'specialties_id' => 'string',
        'type' => 'string',
        'total_points' => 'string',
        'status' => 'string',
        'email_verified_at' => 'datetime'
    ];


    public function doctor()
    {
        return $this->belongsTo(SpecialtieModel::class,'specialties_id');
    }
}
