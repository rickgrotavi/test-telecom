<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentsType extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_type',
        'serial_number_mask'
    ];

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'equipment_id', 'id');
    }
}
