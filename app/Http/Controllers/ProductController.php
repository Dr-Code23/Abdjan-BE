<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
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
     * @return array|JsonResponse
     */
    public function show($product): array|JsonResponse
    {
        $product = $this->productService->show($product);

        if(is_array($product)){
            return $product;
        }

        return $this->notFoundResponse(
            translateErrorMessage('product' , 'not_found')
        );
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
}
