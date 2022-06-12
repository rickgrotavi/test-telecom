<?php

namespace App\Services;

use App\Http\Requests\EquipmentRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\EquipmentsType;
use App\Rules\CheckMask;
use Exception;
use Illuminate\Support\Facades\Validator;

class OperateEquipmentService
{

    /**
     *  Store Equipment
     *
     * @param EquipmentRequest $request
     * @return void
     * @throws Exception
     */
    public function store(EquipmentRequest $request): void
    {
        $equipmentId = $request->get('equipment_id');
        $description = $request->get('description');

        if (gettype($request->get('serial_number')) == 'array') {
            foreach ($request->get('serial_number') as $item) {
                $equipment = [
                    'equipment_id' => $equipmentId,
                    'description' => $description,
                    'serial_number' => $item
                ];
                $this->createEquipment($equipment);
            }
        } else {
            $equipment = [
                'equipment_id' => $equipmentId,
                'description' => $description,
                'serial_number' => $request->get('serial_number'),
            ];
            $this->createEquipment($equipment);
        }
    }

    /**
     * Create Equipment
     *
     * @param array $equipment
     * @return void
     * @throws Exception
     */
    private function createEquipment(array $equipment): void
    {
        $equipmentId = $equipment['equipment_id'];

        $serialNumberMask = EquipmentsType::find($equipmentId)->serial_number_mask;

        $equipment += ['serial_number_mask' => $serialNumberMask];

        $this->validateSerialNumber($equipment);

        Equipment::create($equipment);
    }


    /**
     * Validate serial number on create/update Equipment
     *
     * @param array $equipment
     * @return void
     * @throws Exception
     */
    private function validateSerialNumber(array $equipment): void
    {
        $validator = Validator::make($equipment, [
            "serial_number" => ['required', 'unique:equipments', new CheckMask($equipment['serial_number_mask'])],
        ]);
        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
    }


    /**
     * Update Equipment
     *
     * @param EquipmentRequest $request
     * @param int $id
     * @return Equipment
     * @throws Exception
     */
    public function update(EquipmentRequest $request, int $id): Equipment
    {
        $equipment = Equipment::findOrFail($id);

        if (!$equipment) {
            throw new Exception("Equipment not found");
        }

        $eId = $request->get('equipment_id');

        $serialNumberMask = EquipmentsType::find($eId)->serial_number_mask;

        $equipmentUpdate = array_merge($request->all(), ['serial_number_mask' => $serialNumberMask]);

        $this->validateSerialNumber($equipmentUpdate);

        $equipment->update($equipmentUpdate);

        return $equipment;
    }

    /**
     * Delete Equipment by id
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $equipment = Equipment::find($id);
        $equipment->delete();
    }

    /**
     * Show Equipment by id
     *
     * @param int $id
     * @return EquipmentResource
     */
    public function show(int $id): EquipmentResource
    {
        $equipment = Equipment::find($id);
        return EquipmentResource::make($equipment);
    }
}
