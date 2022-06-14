<?php

namespace App\Services;

use App\Http\Requests\EquipmentRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\EquipmentsType;
use App\Rules\CheckMask;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;

class OperateEquipmentService
{

    /**
     *  Store Equipment
     *
     * @param EquipmentRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store(EquipmentRequest $request): JsonResponse
    {
        $equipments = $request->validated();

        $equipmentId = $equipments['equipment_id'];
        $description = $equipments['description'];

        if (is_array($equipments['serial_number'])) {

            $createdEquipments = collect();
            foreach ($equipments['serial_number'] as $item) {
                $equipment = [
                    'equipment_id' => $equipmentId,
                    'description' => $description,
                    'serial_number' => $item
                ];
                $createdEquipments->push(EquipmentResource::make($this->createEquipment($equipment)));
            }


            return response()->json([
                'status' => true,
                'equipments' => $createdEquipments,
            ]);

        }

        $equipment = [
            'equipment_id' => $equipmentId,
            'description' => $description,
            'serial_number' => $equipments['serial_number'],
        ];
        $createdEquipment = $this->createEquipment($equipment);

        return response()->json([
            'status' => true,
            'equipments' => EquipmentResource::make($createdEquipment),
        ]);
    }

    /**
     * Create Equipment
     *
     * @param array $equipment
     * @return array
     * @throws Exception
     */
    private function createEquipment(array $equipment): array
    {
        $equipmentId = $equipment['equipment_id'];

        $serialNumberMask = EquipmentsType::find($equipmentId)->serial_number_mask;

        $equipment += ['serial_number_mask' => $serialNumberMask];

        $validatedEquipment = $this->validateSerialNumber($equipment);

        if ($validatedEquipment->fails()) {
            $equipment['id'] = null;
            $equipment['status'] = $validatedEquipment->errors()->first();
            return $equipment;
        }
        $equipment = Equipment::create($equipment);

        return [
            'id' => $equipment->id,
            'serial_number' => $equipment->serial_number,
            'serial_number_mask' => $serialNumberMask,
            'description' => $equipment->description,
            'status' => 'stored'
        ];
    }


    /**
     * Validate serial number on create/update Equipment
     *
     * @param array $equipment
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateSerialNumber(array $equipment): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($equipment, [
            "serial_number" => ['required', 'unique:equipments', new CheckMask($equipment['serial_number_mask'])],
        ]);
    }


    /**
     * Update Equipment
     *
     * @param EquipmentRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function update(EquipmentRequest $request, int $id): JsonResponse
    {
        try {
            $equipment = Equipment::findOrFail($id);

            if (!$equipment) {
                throw new Exception("Equipment not found");
            }

            $eId = $request->get('equipment_id');

            $serialNumberMask = EquipmentsType::find($eId)->serial_number_mask;

            $equipmentUpdate = array_merge($request->all(), ['serial_number_mask' => $serialNumberMask]);

            if ($this->validateSerialNumber($equipmentUpdate)->fails()) {
                throw new Exception($this->validateSerialNumber($equipmentUpdate)->errors()->first());
            }

            $equipment->update($equipmentUpdate);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => true,
            'equipment' => EquipmentResource::make($equipment)
        ]);


    }

    /**
     * Delete Equipment by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $equipment = Equipment::find($id);
            $equipment->delete();
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => "Equipment deleted"
        ]);
    }

    /**
     * Show Equipment by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $equipment = EquipmentResource::make(Equipment::find($id));
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => true,
            'equipment' => $equipment
        ]);
    }

    /**
     * Display a listing of the resource with paginate.
     *
     * @param $request
     * @return AnonymousResourceCollection
     */
    public function getBySearchWithPaginate($request): AnonymousResourceCollection
    {
        $paginateCount = $request->get('paginate') ?? 25;

        if ($request->has('search')) {
            return EquipmentResource::collection(Equipment::where('description', 'like', '%' . $request->get("search") . '%')->paginate($paginateCount));
        }

        return EquipmentResource::collection(Equipment::paginate($paginateCount));
    }
}
