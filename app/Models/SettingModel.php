<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SettingModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'setting';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
    ];
}
