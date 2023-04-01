<?php

namespace App\Http\Controllers;

use App\Actions\ChangeRecordStatus;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\ChangeRecordStatusRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Services\FileOperationService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public static string $collectionName = 'brands';
    use HttpResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $brands = Brand::where(function ($query) {
            if (isPublicRoute()) {
                $query->where('status', true);
            }
        }
        )
            ->with('image')
            ->get();

        return $this->resourceResponse(
            BrandResource::collection(
                $brands
            )
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand): JsonResponse
    {
        $brand->loadMissing(['image']);
        return $this->resourceResponse(
            new BrandResource(
                $brand,
                ['name' => $brand->getTranslations('name')]
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brand , FileOperationService $fileOperationService): JsonResponse
    {
        $errors = [];

        checkIfNameExists(Brand::class, $request, $errors, $brand->id);

        if (!$errors) {
            $validatedData = $request->validated();
            if ($request->hasFile('img')) {

                // Delete The Old Image First
                $fileOperationService->removeImage(
                    $brand->getFirstMedia()
                );

                $fileOperationService->storeImageFromRequest($brand , static::$collectionName);
            }

            $brand->update($validatedData);

            return $this->successResponse(
                msg:translateSuccessMessage('brand', 'updated')
            );
        }

        return $this->validationErrorsResponse($errors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request , FileOperationService $fileOperationService): JsonResponse
    {
        // Check If Any Brand Name Exists
        $errors = [];
        checkIfNameExists(Brand::class, $request, $errors);

        if (!$errors) {
            $validatedData = $request->validated();

            $brand = Brand::create($validatedData);
            $fileOperationService->storeImageFromRequest(
                $brand,
                static::$collectionName,
            );

            return $this->createdResponse(
                msg: translateSuccessMessage('brand', 'created')
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
            msg: translateSuccessMessage('brand', 'deleted')
        );
    }
}
