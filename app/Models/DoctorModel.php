<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DoctorModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'doctors';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'specialties_id' => 'string',
        'type' => 'string',
        'total_points' => 'string',
        'status' => 'string'
    ];

    public function doctor()
    {
        return $this->belongsTo(SpecialtieModel::class,'specialties_id');
    }
}
