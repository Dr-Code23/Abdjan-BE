<?php

namespace App\Http\Controllers;

use App\Http\Requests\AboutUsRequest;
use App\Http\Resources\AboutUsResource;
use App\Models\AboutUs;
use App\Models\Ad;
use App\Services\FileOperationService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class AboutUsController extends Controller
{
    use HttpResponse;

    public static string $collectionName = 'about_us';

    /**
     * Display the specified resource.
     */
    public function show(): JsonResponse
    {
        $aboutUs = AboutUs::with('image')->first();

        $fullyTranslatedContent = [];

        if(isNotPublicRoute()){
            $fullyTranslatedContent = [
                'name' => $aboutUs->getTranslations('name'),
                'description' => $aboutUs->getTranslations('description')
            ];
        }
        return $this->resourceResponse(
            new AboutUsResource($aboutUs , $fullyTranslatedContent)
        );
    }

    public function update(AboutUsRequest $request , FileOperationService $fileOperationService): JsonResponse
    {
        $data = $request->validated();
        $aboutUs = AboutUs::find(1);
        $image = $aboutUs->getFirstMedia(static::$collectionName);

        if(isset($data['image'])){
            $fileOperationService->removeImage($image);
            $fileOperationService->storeImageFromRequest(
                $aboutUs,
                static::$collectionName,
                'image'
            );
        }

        $aboutUs->update($data);

        return $this->successResponse(
            msg:translateSuccessMessage('about_us' , 'updated')
        );
    }

}
