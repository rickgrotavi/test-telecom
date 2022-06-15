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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;


class OperateEquipmentService
{

    private Collection $validEquipment;
    private Collection $invalidEquipment;

    public function __construct()
    {
        $this->validEquipment = new Collection();
        $this->invalidEquipment = new Collection();
    }

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

        $equipmentId = $equipments['equipments_type_id'];
        $description = $equipments['description'];

        $equipmentsSN = collect($equipments['serial_number']);

        if ($equipmentsSN->isNotEmpty()) {
            $creationEquipment = $equipmentsSN->unique()->map(function ($serialNumber) use ($description, $equipmentId) {
                return [
                    'serial_number' => $serialNumber,
                    'equipments_type_id' => $equipmentId,
                    'description' => $description,
                ];
            });
            $this->validateCreation($creationEquipment);
            $this->createEquipments();

            return response()->json([
                'status' => true,
                'invalid_equipments' => $this->invalidEquipment->isNotEmpty() ? $this->invalidEquipment->toArray() : null
            ]);
        }
        throw new Exception('Failed to save');
    }

    /**
     * @param Collection $equipments
     * @return void
     */
    private function validateCreation(Collection $equipments): void
    {
        $equipments->each(function ($equipment) {
            $this->validateSerialNumber(collect($equipment));
        });
    }

    /**
     * Create Equipment
     *
     */
    private function createEquipments(): void
    {
        $this->validEquipment->each(function ($equipment) {
            Equipment::create($equipment->toArray());
        });
    }


    /**
     * Validate serial number
     *
     */
    private function validateSerialNumber(Collection $equipment): void
    {
        $serialNumberMask = EquipmentsType::find($equipment['equipments_type_id'])->serial_number_mask;

        $validator = Validator::make($equipment->toArray(), [
            'serial_number' => ['required', 'unique:equipments', new CheckMask($serialNumberMask)],
        ]);

        if ($validator->fails()) {
            $this->invalidEquipment->push($equipment->put('message', $validator->getMessageBag()));
        } else {
            $this->validEquipment->push($equipment);
        }
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

        $equipment = Equipment::findOrFail($id);

        if (!$equipment) {
            throw new Exception("Equipment not found");
        }

        $eId = $request->get('equipments_type_id');

        $serialNumberMask = EquipmentsType::find($eId)->serial_number_mask;

        $validator = Validator::make($request->toArray(), [
            'serial_number' => ['required', 'unique:equipments', new CheckMask($serialNumberMask)],
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }

        $equipment->update($request->all());


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
        $equipment = Equipment::find($id);
        $equipment->delete();

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
        $equipment = EquipmentResource::make(Equipment::find($id));
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
