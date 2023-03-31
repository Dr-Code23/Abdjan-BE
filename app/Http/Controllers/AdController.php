<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdResource;
use App\Services\AdService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
