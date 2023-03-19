<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeasurementUnitRequest;
use App\Http\Resources\NameWithIdResource;
use App\Models\MeasureUnit;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class MeasurementUnitController extends Controller
{
    use HttpResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->resourceResponse(
            NameWithIdResource::collection(MeasureUnit::all())
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MeasurementUnitRequest $request): JsonResponse
    {
        $unit = MeasureUnit::create($request->validated());

        return $this->createdResponse(new NameWithIdResource($unit));
    }

    /**
     * Display the specified resource.
     */
    public function show(MeasureUnit $unit): JsonResponse
    {
        return $this->resourceResponse(new NameWithIdResource($unit));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MeasurementUnitRequest $request, MeasureUnit $unit): JsonResponse
    {
        $unit->name = $request->input('name');
        $unit->save();

        return $this->successResponse(
            new NameWithIdResource($unit) ,
            translateSuccessMessage('unit' , 'updated')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MeasureUnit $unit): JsonResponse
    {
        $unit->delete();

        return $this->successResponse(
            msg: translateSuccessMessage('unit' , 'deleted')
        );
    }
}
