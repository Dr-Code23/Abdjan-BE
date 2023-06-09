<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    use HttpResponse;


    public function __construct(private readonly ProductService $productService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $products = $this->productService->index();

        return ProductResource::collection($products);

    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {

        $result = $this->productService->store($request);

        if(is_bool($result) && $result){
            return $this->successResponse(
                msg:translateSuccessMessage('product' , 'updated')
            );
        }

        return $this->validationErrorsResponse($result);
    }


    /**
     * @param int $product
     * @return JsonResponse
     */
    public function show(int $product): JsonResponse
    {
        $product = $this->productService->show($product);


        if($product instanceof Product){

            $fullyTranslatedContent = [];

            // request()->routeIs('public') not worked !

            if(isNotPublicRoute()){
                $fullyTranslatedContent['name'] = $product->getTranslations('name');
                $fullyTranslatedContent['description'] = $product->getTranslations('description');
            }

            return $this->resourceResponse(
                new ProductResource(
                    $product ,
                    $fullyTranslatedContent,
                    true,
                )
            );
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

        if(is_bool($result) && $result){
            return $this->successResponse(
                msg:translateSuccessMessage('product' , 'updated')
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
     * @return JsonResponse
     */
    public function showAllForPublicUser(): JsonResponse
    {
        $products = $this->productService->showAllProductsForPublicUser();

        return $this->resourceResponse($products);

    }
}
