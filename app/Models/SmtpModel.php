<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class SmtpModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'smtp';
    protected $guarded = array();
}
