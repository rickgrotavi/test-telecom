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
        return $this->operateEquipmentService->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->operateEquipmentService->show($id);
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
        return $this->operateEquipmentService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->operateEquipmentService->delete($id);
    }
}
