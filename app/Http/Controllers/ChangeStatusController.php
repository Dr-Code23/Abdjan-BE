<?php

namespace App\Http\Controllers;

use App\Actions\ChangeRecordStatus;
use App\Http\Requests\ChangeRecordStatusRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ChangeStatusController extends Controller
{
    use HttpResponse;

    /**
     * Allowed models to change status
     * @var array|string[]
     */
    private array $allowedList = [
        'category' => Category::class,
        'service' => Service::class,
        'brand' => Brand::class,
        'product' => Product::class,
        'user' => User::class,
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
        if (isset($this->allowedList[$type])) {

            $updated = $changeRecordStatus->handle(
                $this->allowedList[$type],
                $id,
                $request->validated()['status']
            );

            if ($updated) {
                return $this->successResponse(
                    msg: translateSuccessMessage('status', 'updated')
                );
            }
        }

        return $this->notFoundResponse(translateErrorMessage('key' , 'not_found'));
    }
}
