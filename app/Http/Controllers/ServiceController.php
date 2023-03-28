<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelExistsException;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Services\ServiceClassService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    use HttpResponse;
    public static string $serviceCollectionName = 'services';
    public function __construct(private readonly ServiceClassService $service)
    {}
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->resourceResponse(
            ServiceResource::collection(
                $this->service->index()
            )
        );
    }

    /**
     * @param ServiceRequest $request
     * @return JsonResponse
     */
    public function store(ServiceRequest $request): JsonResponse|string
    {
        return 'in store';
    }


    /**
     * @param int $service
     * @return JsonResponse
     */
    public function show(int $service): JsonResponse
    {
        $service = $this->service->show($service);

        if($service instanceof Service){

            $fullyTranslatedContent = [];

            // request()->routeIs('public') not worked !

            if(isNotPublicRoute()){
                $fullyTranslatedContent['name'] = $service->getTranslations('name');
                $fullyTranslatedContent['description'] = $service->getTranslations('description');
            }

            return $this->resourceResponse(
                new ServiceResource(
                    $service,
                    $fullyTranslatedContent,
                    true
                )
            );
        }

        return $this->notFoundResponse(
            translateErrorMessage('service' , 'not_found')
        );
    }


    /**
     * @param ServiceRequest $request
     * @param Service $service
     * @return JsonResponse
     */
    public function update(ServiceRequest $request, Service $service): JsonResponse
    {

    }

    /**
     * @param Service $service
     * @return JsonResponse
     */
    public function destroy(Service $service): JsonResponse
    {
        $service->delete();

        return $this->successResponse(
            msg: translateSuccessMessage('service' , 'deleted')
        );
    }
}
