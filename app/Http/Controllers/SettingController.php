<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use App\Services\FileOperationService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    use HttpResponse;
    public static string $collectionName = 'settings';

    public function show(): JsonResponse
    {
        $settings = Setting::find(1);

        $fullyTranslatedContent = [];

        if(isNotPublicRoute()){
            $fullyTranslatedContent = [
                'name' => $settings->getTranslations('name'),
            ];
        }

        return $this->resourceResponse(
            new SettingResource($settings , $fullyTranslatedContent)
        );
    }

    public function update(SettingRequest $request , FileOperationService $fileOperationService): JsonResponse
    {
        $data = $request->validated();
        $settings = Setting::with('logo')->first();
        $logo = $settings->getFirstMedia(static::$collectionName);
        if(isset($data['logo'])){
            $fileOperationService->removeImage($logo);
            $fileOperationService->storeImageFromRequest(
                $settings,
                static::$collectionName,
                'logo'
            );
        }

        $settings->update($data);

        return $this->successResponse(
            msg: translateSuccessMessage('settings' , 'updated')
        );
    }
}
