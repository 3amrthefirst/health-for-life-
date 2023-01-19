<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SpecialtieModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'specialties';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
    ];
}
