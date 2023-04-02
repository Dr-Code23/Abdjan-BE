<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdRequest;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Services\AdService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class AdController extends Controller
{
    use HttpResponse;
    public static string $collectionName = 'ads';

    /**
     * @var AdService
     */
    protected AdService $adService;
    public function __construct(AdService $adService){
        $this->adService = $adService;
    }
    public function index(): JsonResponse
    {
        return $this->resourceResponse(
            AdResource::collection($this->adService->index())
        );
    }

    public function show(Ad $ad): JsonResponse
    {
        $ad = $this->adService->show($ad->id);

        if($ad){
            return $this->resourceResponse(new AdResource($ad , [
                'title' => $ad->getTranslations('title'),
                'description' => $ad->getTranslations('description')
            ]));
        }

        return $this->notFoundResponse(
            translateErrorMessage('ad' , 'not_found')
        );
    }

    public function store(AdRequest $request): JsonResponse
    {
        $this->adService->store($request->validated());

        return $this->createdResponse(
            msg:translateSuccessMessage('ad' , 'created')
        );
    }

    public function update(AdRequest $request , Ad $ad): JsonResponse
    {
        $this->adService->update($request->validated() , $ad);

        return $this->successResponse(
            msg:translateSuccessMessage('ad' , 'updated')
        );
    }

    public function destroy(Ad $ad): JsonResponse
    {
        $ad->delete();

        return $this->successResponse('ad' , 'deleted');
    }
}
