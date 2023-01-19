<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class ReviewsModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'reviews';
    protected $guarded = array();
}
