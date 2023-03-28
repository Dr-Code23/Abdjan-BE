<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadImageRequest;
use App\Services\FileOperationService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class FileManagerController extends Controller
{
    use HttpResponse;

    /**
     * @param UploadImageRequest $request
     * @param FileOperationService $fileOperationService
     * @return JsonResponse
     */
    public function uploadTemporaryImage(UploadImageRequest $request, FileOperationService $fileOperationService): JsonResponse
    {
        return $this->successResponse(
            [$fileOperationService->uploadFileTemporary($request)],
            translateSuccessMessage('file', 'uploaded')
        );
    }
}
