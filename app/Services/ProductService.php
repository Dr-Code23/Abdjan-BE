<?php

namespace App\Services;

use App\Http\Controllers\ProductController;
use App\Http\Requests\ServiceRequest;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProductService
{
    private string $mediaCollectionName = 'products';
    /**
     * @return Collection|array
     */
    public function index(): Collection|array
    {
        return Product::latest('id')->paginate(paginationCountPerPage());
    }

    /**
     * @param int $product
     * @return Builder|Model|null
     */
    public function show(int $product): Builder|Model|null
    {
        $product = Product::with(
            [
                'brand',
                'attribute',
                'unit',
                'category' => fn($query) => $query->select(['id', 'name']),
                'images'
            ]
        )
            ->where('id', $product)
            ->where(function($query){
                if(isPublicRoute()){
                    $query->where('status' , true);
                }
            })
            ->first();

        return $product ?: null;
    }

    /**
     * @param $request
     * @return bool|array
     */
    public function store($request): bool|array
    {
        return $this->storeOrUpdate($request);
    }


    /**
     * @param $request
     * @param $product
     * @return bool|array
     */
    public function update($request , $product): bool|array
    {
        return $this->storeOrUpdate($request , $product);
    }

    /**
     * @param $request
     * @param int|null $productId
     * @return bool|array
     */
    private function storeOrUpdate($request,  int $productId = null)
    {
        $fileOperationService = new FileOperationService();
        $errors = [];

        checkIfNameExists(
            Product::class ,
            $request ,
            $errors,
            $productId ?: null ,
        );

        if(!$errors)
        {
            $validatedData = $request->validated();
            if(!$productId) {
                $product = Product::create($validatedData);

                $fileOperationService->storeImages(
                    $validatedData['images'] ?? [],
                    $this->mediaCollectionName,
                    $product
                );
            }
            else {

                $product = Product::where('id', $productId)->first();

                if($product){
                    $fileOperationService->removeOldImagesAndStoreNew(
                        $product,
                        $this->mediaCollectionName,
                        $validatedData['images'] ?? [],
                        $validatedData['keep_images'] ?? [],
                        $errors
                    );

                    $product->update($validatedData);
                    return $errors;
                }
                else {
                    $errors['product'] = translateErrorMessage('product' , 'not_found');
                }
            }
            if(!$errors) {
                return true;
            }
        }

        return $errors;
    }

    public function showAllProductsForPublicUser(): Collection|array
    {
        $data = request()->all();
        $orderBy = $data['order_by'] ?? 'asc';
        if(!in_array($orderBy , ['asc' , 'desc'])){
            $orderBy = 'asc';
        }
        $products =  Product::with(
            [
                'images' => fn(MorphMany $query) => $query->limit(1)
            ]
        )
            ->where(function($query) use ($data){


                $allowedSearchInputs = [
                    'brand',
                    'category',
                ];

                foreach($data as $key=>$value){
                    if(in_array($key , $allowedSearchInputs) && is_numeric($value)){

                        $query->where($key."_id" , $value);
                    }
                }
            })
            ->where(function($query){
                if(request()->has('search_value')) {
                    foreach (config('translatable.locales') as $locale) {
                        if($locale == app()->getLocale()) {
                            $query->where('name->' . $locale,'like', '%' . request('search_value').'%');
                        } else {
                            $query->orWhere('name->' . $locale,'like', '%' . request('search_value').'%');
                        }
                    }
                }
            })
            ->orderBy('id' , $orderBy)
            ->where('status' , true)
            ->get([
            'id',
            'name',
            'unit_price',
        ]);

        $data = [];

        foreach($products as $product){
            $data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => round($product->unit_price),
                'img' => $product->images->first()->original_url ?? asset('/storage/default/product.webp')
            ];
        }

        return $data;
    }
}
