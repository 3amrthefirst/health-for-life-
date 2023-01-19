<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MedicineModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'medicine';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'status' => 'string'
    ];
}
