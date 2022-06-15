<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
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
     * @throws Exception
     */
    public function store(EquipmentRequest $request): JsonResponse
    {
        try {
            return $this->operateEquipmentService->store($request);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
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
            return $this->operateEquipmentService->show($id);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param EquipmentRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function update(EquipmentRequest $request, int $id): JsonResponse
    {
        try {
            return $this->operateEquipmentService->update($request, $id);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
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
            return $this->operateEquipmentService->delete($id);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
