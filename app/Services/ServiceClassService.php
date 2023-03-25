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
        return $this->getServiceWithSingleTranslation();
    }


    public function show($service): Model|Builder|null
    {
        return Service::with(
            [
                'category' => fn($query) => $query->select(['id', 'name'])
            ]
        )
            ->where('id', $service)
            ->first();
    }

    /**
     * @param $request
     * @return Model|Collection|Builder|array|null
     *
     * @throws ModelExistsException
     */
    public function store($request): Model|Collection|Builder|array|null
    {
        return $this->storeOrUpdate($request);
    }

    /**
     * @throws ModelExistsException
     */
    public function update($request , $service): Model|Collection|Builder|array|null
    {
        return $this->storeOrUpdate($request , $service->id);
    }

    /**
     * @param $request
     * @param int|null $serviceId
     * @return Model|Collection|Builder|array|null
     * @throws ModelExistsException
     */
    private function storeOrUpdate($request, int $serviceId = null): Model|Collection|Builder|array|null
    {
        $errors = [];
//        $allTitles = [];
//        foreach(config('translatable.locales') as $locale){
//            if($request->has('name:'.$locale) && $request->input('name:'.$locale)){
//                $allTitles[] = $request->input('name:'.$locale);
//            }
//        }

        checkIfNameExists(Service::class , $request , $errors , $serviceId);
//        $titleExists = ServiceTranslation::whereIn('name' , $allTitles)
//            ->where(function($query) use ($serviceId){
//                if($serviceId){
//                    $query->where('service_id' ,'!=', $serviceId);
//                }
//            })
//            ->first(['id' , 'name']);

        if(!$errors)
        {
            $validatedData = $request->validated();
            if(!$serviceId) {
                $service = Service::create($validatedData);
            }
            else {
                $service = Service::where('id' , $serviceId)->first();

                // Not The Best Solution
                $service->update($validatedData);
            }
            return $this->getServiceWithSingleTranslation($serviceId ?: $service->id);
        }

        return $errors;
    }

    private function getServiceWithSingleTranslation(int $serviceId = null): Model|Collection|Builder|array|null
    {
        $service = Service::with(
            [
                'category' => fn($query) => $query->select(['id', 'name'])
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
