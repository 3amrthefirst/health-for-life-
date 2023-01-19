<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CurrencyModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'currency';
    protected $guarded = array();
}
