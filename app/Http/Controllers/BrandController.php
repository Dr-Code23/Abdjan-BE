<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use HttpResponse;
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        return $this->resourceResponse(BrandResource::collection(Brand::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request): JsonResponse
    {
        return $this->createdResponse(
            new BrandResource(Brand::create($request->validated())),
            translateSuccessMessage('brand' , 'created')
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand): JsonResponse
    {
        return $this->resourceResponse(
            new BrandResource($brand)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brand): JsonResponse
    {
        $brand->update($request->validated());
        return $this->success(
            new BrandResource($brand),
            translateSuccessMessage('brand' , 'updated')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand): JsonResponse
    {
        $brand->delete();
        return $this->success(null , translateSuccessMessage('brand' , 'deleted'));
    }
}
