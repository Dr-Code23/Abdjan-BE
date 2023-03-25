<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;
use App\Http\Resources\NameWithIdResource;
use App\Models\Brand;
use App\Services\BrandService;
use App\Services\FileOperationService;
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
            BrandResource::collection(Brand::all())
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
            $validatedData = $request->validated();
            $fileName = explode('/' , $request->file('img')->store('public/brands'));
            $fileName = $fileName[count($fileName)-1];
            $validatedData['img'] = $fileName;

            return $this->createdResponse(
                new BrandResource(
                    Brand::create($validatedData)
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
            new BrandResource(
                $brand ,
                ['name' => $brand->getTranslations('name')]
            )
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
            $validatedData = $request->validated();
            if($request->hasFile('img')) {

                // Delete The Old Image First
                FileOperationService::deleteImage('brands/'.$brand->img);

                $fileName = explode('/', $request->file('img')->store('public/brands'));
                $fileName = $fileName[count($fileName) - 1];
                $validatedData['img'] = $fileName;
            }

            $brand->update($validatedData);

            return $this->successResponse(
                new BrandResource($brand),
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
        FileOperationService::deleteImage("brands/{$brand->img}");
        $brand->delete();

        return $this->successResponse(
             msg:translateSuccessMessage('brand' , 'deleted')
        );
    }
}
