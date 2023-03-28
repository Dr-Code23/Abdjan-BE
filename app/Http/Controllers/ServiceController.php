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
    public function store(ServiceRequest $request)
    {
        $result = $this->service->store($request);

        if(is_bool($result) && $result){
            return $this->createdResponse(
                msg:translateSuccessMessage('service' , 'created')
            );
        }

        return $this->validationErrorsResponse($result);
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
        $result = $this->service->update($request , $service);

        if(is_bool($result) && $result){
            return $this->successResponse(msg:translateSuccessMessage('service' , 'updated'));
        }

        return $this->validationErrorsResponse($result);
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

    public function showAllServicesForPublicUser(){
        $services = Service::with(['images' => fn($query) => $query->take(1)])
            ->latest('id')
            ->get([
            'id',
            'name',
            'price'
        ]);


        $data = [];
        foreach($services as $service){
            $data[] = [
                'id' => $service->id,
                'name' => $service->name,
                'price' => round($service->price),
                'img' => $service->images->first()->original_url ?? asset('/storage/default/service.png')
            ];
        }
        return $this->resourceResponse($data);
    }
}
