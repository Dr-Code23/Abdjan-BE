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
    public function index(): JsonResponse
    {
        return $this->resourceResponse(
            NameWithIdResource::collection(Brand::all())
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request): JsonResponse
    {

        // Check If Any Brand Name Exists
        $errors = [];
        checkIfNameExists(Brand::class,$request , $errors);

        if(!$errors){
            return $this->createdResponse(
                new NameWithIdResource(
                    Brand::create($request->validated())
                ),
                translateSuccessMessage('brand' , 'created')
            );
        }

        return $this->validationErrorsResponse($errors);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand): JsonResponse
    {
        return $this->resourceResponse(
            [
                'id' => $brand->id,
                'name' => $brand->getTranslations('name' , config('translatable.locales'))
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brand): JsonResponse
    {
        $errors = [];
        checkIfNameExists(Brand::class , $request , $errors , $brand->id);
        if(!$errors){
            $brand->update($request->validated());

            return $this->successResponse(
                new NameWithIdResource($brand),
                translateSuccessMessage('brand' , 'updated')
            );
        }

        return $this->validationErrorsResponse($errors);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand): JsonResponse
    {
        $brand->delete();
        return $this->successResponse(
             msg:translateSuccessMessage('brand' , 'deleted')
        );
    }
}
