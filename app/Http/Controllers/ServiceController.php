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
    public function __construct(private readonly ServiceClassService $service)
    {
    }
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
    public function store(ServiceRequest $request): JsonResponse
    {
        try{
            return $this->createdResponse(
                new ServiceResource($this->service->store($request)),
                translateSuccessMessage('service' , 'created')
            );
        }
        catch(ModelExistsException $e)
        {
            return $this->validationErrorsResponse([
                'name' => $e->getMessage()
            ]);
        }
    }


    /**
     * @param $service
     * @return JsonResponse
     */
    public function show($service): JsonResponse
    {
        $service = $this->service->show($service);

        if($service instanceof Service){
            return $this->resourceResponse(
                new ServiceResource($service)
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
        try{
            return $this->successResponse(
                new ServiceResource($this->service->update($request , $service)),
                translateSuccessMessage('service' , 'updated')
            );
        }
        catch(ModelExistsException $e)
        {
            return $this->validationErrorsResponse([
                'title' => $e->getMessage()
            ]);
        }
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
