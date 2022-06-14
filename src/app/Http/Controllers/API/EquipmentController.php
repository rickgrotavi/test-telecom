<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EquipmentRequest;
use App\Http\Resources\EquipmentResource;
use App\Services\OperateEquipmentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EquipmentController extends Controller
{
    private OperateEquipmentService $operateEquipmentService;

    public function __construct()
    {
        $this->operateEquipmentService = app(OperateEquipmentService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return $this->operateEquipmentService->getBySearchWithPaginate($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EquipmentRequest $request
     * @return JsonResponse
     */
    public function store(EquipmentRequest $request): JsonResponse
    {
        return $this->operateEquipmentService->store($request);
//        try {
//            $this->operateEquipmentService->store($request);
//        } catch (Exception $e) {
//            return response()->json([
//                'status' => false,
//                'message' => $e->getMessage()
//            ]);
//        }
//        return response()->json([
//            'status' => true,
//            'message' => "Equipment stored"
//        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $equipment = $this->operateEquipmentService->show($id);
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
     * Update the specified resource in storage.
     *
     * @param EquipmentRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(EquipmentRequest $request, int $id): JsonResponse
    {
        try {
            $equipment = $this->operateEquipmentService->update($request, $id);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' =>true,
            'message' => "Equipment updated",
            'equipment' => EquipmentResource::make($equipment)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->operateEquipmentService->delete($id);
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
}
