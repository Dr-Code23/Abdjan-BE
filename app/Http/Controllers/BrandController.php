<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Http\Resources\NameWithIdResource;
use App\Models\Brand;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    use HttpResponse;
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        return $this->resourceResponse(NameWithIdResource::collection(Brand::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request): JsonResponse
    {
        return $this->createdResponse(
            new NameWithIdResource(Brand::create($request->validated())),
            translateSuccessMessage('brand' , 'created')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand): JsonResponse
    {
        return $this->resourceResponse(
            new NameWithIdResource($brand)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brand): JsonResponse
    {
        $brand->update($request->validated());
        return $this->successResponse(
            new NameWithIdResource($brand),
            translateSuccessMessage('brand' , 'updated')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand): JsonResponse
    {
        $brand->delete();
        return $this->successResponse(null , translateSuccessMessage('brand' , 'deleted'));
    }
}
