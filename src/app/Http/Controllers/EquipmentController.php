<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Services\OperateEquipmentService;
use Exception;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $paginateCount = (int)$request->get('paginate') ?? 25;

        if ($request->has('search')) {
            return EquipmentResource::collection(Equipment::where('description', 'like', '%' . $request->get("search") . '%')->paginate($paginateCount));
        }

        return EquipmentResource::collection(Equipment::paginate($paginateCount));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EquipmentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EquipmentRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->operateEquipmentService->store($request);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => "Equipment stored"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): \Illuminate\Http\JsonResponse
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Equipment  $equipment
     * @return \Illuminate\Http\Response
     */
    public function edit(Equipment $equipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EquipmentRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(EquipmentRequest $request, int $id): \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): \Illuminate\Http\JsonResponse
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
