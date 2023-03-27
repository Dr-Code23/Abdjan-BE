<?php

namespace App\Http\Controllers;

use App\Actions\ChangeRecordStatus;
use App\Http\Requests\ChangeRecordStatusRequest;
use App\Models\Brand;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ChangeStatusController extends Controller
{
    use HttpResponse;

    private array $allowedList = [
        'category',
        'brand'
    ];

    /**
     * @param ChangeRecordStatusRequest $request
     * @param ChangeRecordStatus $changeRecordStatus
     * @param string $type
     * @param int $id
     * @return JsonResponse
     */
    public function handle(
        ChangeRecordStatusRequest $request,
        ChangeRecordStatus        $changeRecordStatus,
        string                    $type,
        int                       $id
    ): JsonResponse
    {

        if (in_array($type, $this->allowedList)) {
            $updated = $changeRecordStatus->handle(
                Str::ucfirst(Str::lower($request->route('type'))),
                $id,
                $request->validated()['status']
            );

            if($updated){
                return $this->successResponse(
                    msg: translateSuccessMessage('status', 'updated')
                );
            }
        }

        return $this->notFoundResponse();
    }

}
