<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PrescriptionModel extends Authenticatable
{
    use Notifiable;

    protected $table = 'prescription';
    protected $guarded = array();
    protected $casts = [
        'id' => 'string',
        'appointment_id' => 'string',
        'medicine_id' => 'string',
        'status' => 'string'
    ];

    public function medicine()
    {
        return $this->belongsTo(MedicineModel::class,'medicine_id');
    }
}
