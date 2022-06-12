<?php

namespace Database\Seeders;

use App\Models\EquipmentsType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(EquipmentsType::all()->count() == 0){
            EquipmentsType::create(['name'=> 'TP-Link TL-WR74', 'serial_number_mask' => 'XXAAAAAXAA']);
            EquipmentsType::create(['name'=> 'D-Link DIR-300', 'serial_number_mask'=>'NXXAAXZXaa']);
            EquipmentsType::create(['name'=> 'D-Link DIR-300 S', 'serial_number_mask' => 'NXXAAXZXXX']);
        }
    }
}
