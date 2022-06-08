<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EquipmentsType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'equipment_type',
        'serial_number_mask'
    ];

    public function equipment()
    {
        return $this->hasMany(Equipment::class, 'equipment_id', 'id');
    }
}
