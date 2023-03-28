<?php

namespace App\Services;

use App\Exceptions\ModelExistsException;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServiceClassService
{
    public function index(): Collection|array
    {
        return Service::all();
    }


    public function show($service): Model|Builder|null
    {
        return Service::with(
            [
                'category' => fn($query) => $query->select(['id', 'name']),
                'images'
            ]
        )
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


    private function storeOrUpdate($request, int $serviceId = null): bool|array
    {
        $errors = [];
        checkIfNameExists(
            Service::class ,
            $request ,
            $errors ,
            $serviceId
        );

        if(!$errors)
        {
            $validatedData = $request->validated();
            if(!$serviceId) {
//                info($validatedData);
                die;
                $service = Service::create($validatedData);

//                foreach($validatedData){
//
//                }


            }
            else {
                $service = Service::where('id' , $serviceId)->first();
                if($service) {
                    // Not The Best Solution
                    $service->update($validatedData);
                } else {
                    return false;
                }
            }

            return true;
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
