<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelExistsException;
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
        try{
            return $this->createdResponse(
                new ProductResource($this->productService->store($request)),
                translateSuccessMessage('product' , 'created')
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
     * @param $product
     * @return JsonResponse
     */
    public function show($product): JsonResponse
    {
        $product = $this->productService->show($product);
        if($product instanceof Product){
            return $this->resourceResponse(
                new ProductResource($product)
            );
        }

        return $this->notFoundResponse(
            translateErrorMessage('product' , 'not_found')
        );
    }


    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        try{
            return $this->successResponse(
                new ProductResource($this->productService->update($request , $product)),
                translateSuccessMessage('product' , 'updated')
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
