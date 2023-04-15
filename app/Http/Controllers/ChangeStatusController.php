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

class ChangeStatusController extends Controller
{
    use HttpResponse;

    /**
     * Allowed models to change status
     * @var array|string[]
     */
    private array $allowedList = [
        'category' => ['model' => Category::class, 'permission' => 'category_permissions'],
        'service' => ['model' => Service::class, 'permission' => 'service_management'],
        'brand' => ['model' => Brand::class, 'permission' => 'brand_management'],
        'product' => ['model' => Product::class, 'permission' => 'product_management'],
        'user' => ['model' => User::class, 'permission' => 'user_management'],
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

            $loggedUserHasPermission = auth()->user()->hasPermissionTo($this->allowedList[$type]['permission']);

            if ($loggedUserHasPermission) {
                if ($type != 'user' || ($id != auth()->id())) {

                    $updated = $changeRecordStatus->handle(
                        $this->allowedList[$type]['model'],
                        $id,
                        $request->validated()['status']
                    );

                    if ($updated) {
                        return $this->successResponse(
                            msg: translateSuccessMessage('status', 'updated')
                        );
                    }
                }

            }

        }

        return $this->notFoundResponse(translateErrorMessage('key', 'not_found'));
    }
}
