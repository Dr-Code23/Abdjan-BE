<?php

namespace App\Services;

use App\Exceptions\ModelExistsException;
use App\Facades\Search;
use App\Http\Controllers\ServiceController;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServiceClassService
{
    public function index()
    {
        return Service::latest('id')->paginate(paginationCountPerPage());
    }


    public function show($service): Model|Builder|null
    {
        return Service::with(
            [
                'category' => fn($query) => $query->select(['id', 'name']),
                'images'
            ]
        )
            ->where(function($query){
                Search::searchForHandle(
                    $query ,
                    ['name' , 'description'] ,
                    request('handle'),
                    ['name' , 'description']
                );
            })
            ->where('id', $service)
            ->first();
    }


    public function store($request): bool|array
    {
        return $this->storeOrUpdate($request);
    }


    public function update($request , $service): bool|array
    {
        return $this->storeOrUpdate($request , $service->id);
    }


    private function storeOrUpdate($request, $serviceId = null): bool|array
    {
        $fileOperationService = new FileOperationService();
        $errors = [];
        checkIfNameExists(
            Service::class ,
            $request ,
            $errors ,
            $serviceId ?:null
        );

        if(!$errors)
        {
            $validatedData = $request->validated();
            if(!$serviceId) {
                $service = Service::create($request->validated());

                //TODO Store Images For Service

                $fileOperationService->storeImages(
                    $validatedData['images'] ?? [],
                    ServiceController::$serviceCollectionName,
                    $service
                );

            }
            else {
                $service = Service::where('id' , $serviceId)->first();
                if($service) {

                    $fileOperationService->removeOldImagesAndStoreNew(
                        $service,
                        ServiceController::$serviceCollectionName,
                        $validatedData['images'] ?? [],
                        $validatedData['keep_images'] ?? [],
                        $errors
                    );

                    $service->update($validatedData);

                } else {
                    $errors['service'] = translateErrorMessage('service','not_found');
                }
            }

            if(!$errors) {
                return true;
            }
        }

        return $errors;
    }

    private function getServiceWithSingleTranslation(int $serviceId = null): Model|Collection|Builder|array|null
    {
        $service = Service::with(
            [
                'category' => fn($query) => $query->select(['id', 'name']),

            ]
        )
            ->where(function($query) use ($serviceId){
                if($serviceId){
                    $query->where('id' , $serviceId);
                }
            });

        if($serviceId){
            return $service->first();
        }
        else {
            return $service->get();
        }
    }
}
