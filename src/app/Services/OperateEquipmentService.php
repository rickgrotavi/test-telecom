<?php

namespace App\Services;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Exception;

class OperateEquipmentService
{
    /**
     * @param $request
     * @return void
     */
    public function store($request): void
    {
        if (gettype($request->get('serial_number')) == 'array') {

            $equipmentId = $request->get('equipment_id');
            $description = $request->get('description');

            foreach ($request->get('serial_number') as $item) {
                Equipment::create([
                    'equipment_id' => $equipmentId,
                    'description' => $description,
                    'serial_number' => $item
                ]);
            }
        } else {
            Equipment::create($request->all());
        }
    }

    /**
     * @throws Exception
     */
    public function update($request, int $id)
    {
        $equipment = \App\Models\Equipment::findOrFail($id);

        if (!$equipment) {
            throw new Exception("Equipment not found");
        }

        $equipment->update($request->all());

        return $equipment;
    }

    public function delete($id): void
    {
        $equipment = Equipment::find($id);
        $equipment->delete();
    }

    public function show($id): EquipmentResource
    {
        $equipment = Equipment::find($id);
        return EquipmentResource::make($equipment);
    }
}
