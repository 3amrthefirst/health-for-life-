<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CompanyModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'company';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'status' => 'string'
    ];

}
