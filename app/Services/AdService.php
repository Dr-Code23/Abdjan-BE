<?php

namespace App\Services;

use App\Facades\Search;
use App\Http\Controllers\AdController;
use App\Models\Ad;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AdService
{
    public function index()
    {
        $ads =  Ad::with('image')
            ->where(function($query){
                Search::searchForHandle(
                    $query ,
                    ['title'] ,
                    request('handle'),
                    ['title']
                );
            })
            ->latest('id');
        if(isPublicRoute()){
            $ads = $ads->get();
        }
        else {
            $ads = $ads->paginate(paginationCountPerPage());
        }
            return $ads;
    }

    public function show(int $id): Model|Builder|null
    {
        $ad = Ad::with('image')
            ->where('id' , $id)
            ->first();

        return $ad ?: null;
    }

    public function store(array $data): bool
    {
        $fileOperationService = new FileOperationService();
        $ad = Ad::create($data);
        $fileOperationService->storeImageFromRequest(
            $ad,
            AdController::$collectionName ,
            'image'
        );

        return true;
    }

    public function update(array $data , Ad $ad): bool
    {

        $fileOperationService = new FileOperationService();

        if(isset($data['image'])){
            $adImage = $ad->getFirstMedia(AdController::$collectionName);
            $fileOperationService->removeImage($adImage);
            $fileOperationService->storeImageFromRequest(
                $ad,AdController::$collectionName,'image'
            );
        }

        $ad->update($data);

        return true;
    }
}
