<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LanguageModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'language';
    protected $guarded = array();

}
