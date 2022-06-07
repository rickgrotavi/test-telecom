<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentsTypeResource;
use App\Models\EquipmentsType;
use Illuminate\Http\Request;

class EquipmentsTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $paginateCount = (int)$request->get('paginate') ?? 25;

        if ($request->has('search')) {
            // можно прикрутить настоящий поиск, типа MeiliSearch
            return EquipmentsTypeResource::collection(EquipmentsType::where('name', 'like', '%' . $request->get("search") . '%')->paginate($paginateCount));
        }

        return EquipmentsTypeResource::collection(EquipmentsType::paginate($paginateCount));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
