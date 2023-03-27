<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\UploadImageRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\FileOperationService;
use App\Services\ProductService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use HttpResponse;


    public function __construct(private readonly ProductService $productService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->resourceResponse(
            ProductResource::collection($this->productService->index())
        );
    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {

        $result = $this->productService->store($request);

        if($result instanceof Product){
            return $this->successResponse(
                new ProductResource($result),
                translateSuccessMessage('product' , 'updated')
            );
        }

        return $this->validationErrorsResponse($result);
    }


    /**
     * @param $product
     * @return JsonResponse
     */
    public function show($product): JsonResponse
    {
        $product = $this->productService->show($product);

        if($product instanceof Product){

            $fullyTranslatedContent = [];

            // request()->routeIs('public') not worked !

            if(isNotPublicRoute()){
                $fullyTranslatedContent['name'] = $product->getTranslations('name');
                $fullyTranslatedContent['description'] = $product->getTranslations('description');
            }

            return $this->resourceResponse(new ProductResource($product , $fullyTranslatedContent));
        }

        return $this->notFoundResponse(translateErrorMessage('product' , 'not_found'));
    }

    /**
     * @param ProductRequest $request
     * @param int $product
     * @return JsonResponse
     */
    public function update(ProductRequest $request, int $product): JsonResponse
    {
        $result = $this->productService->update($request, $product);

        if($result instanceof Product){
            return $this->successResponse(
                new ProductResource($result),
                translateSuccessMessage('product' , 'updated')
            );
        }

        return $this->validationErrorsResponse($result);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return $this->successResponse(
            msg: translateSuccessMessage('product' , 'deleted')
        );
    }

    /**
     * @param UploadImageRequest $request
     * @param FileOperationService $fileOperationService
     * @return JsonResponse
     */
    public function uploadImage(UploadImageRequest $request , FileOperationService $fileOperationService): JsonResponse
    {
        return $this->successResponse(
            [$fileOperationService->uploadFileTemporary($request)],
            translateSuccessMessage('file' , 'uploaded')
        );
    }
}
