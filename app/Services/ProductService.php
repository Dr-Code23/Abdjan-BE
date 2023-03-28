<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProductService
{
    /**
     * @return Collection|array
     */
    public function index(): Collection|array
    {
        return Product::all(
            [
                'id' ,
                'name',
                'unit_price',
                'status',
            ]
        );
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
            ->first();

        return $product ?: null;
    }

    /**
     * @param $request
     * @return Model|Collection|Builder|array|null
     */
    public function store($request): Model|Collection|Builder|array|null
    {
        return $this->storeOrUpdate($request);
    }


    /**
     * @param $request
     * @param $product
     * @return Model|Collection|Builder|array|null
     */
    public function update($request , $product): Model|Collection|Builder|array|null
    {
        return $this->storeOrUpdate($request , $product);
    }

//    private function getProductWithSingleTranslation(int $productId = null): Model|Collection|Builder|array|null
//    {
//        $product = Product::with(
//            [
//                'brand',
//                'attribute',
//                'unit',
//                'category' => fn($query) => $query->select(['id', 'name']),
//                'images'
//            ]
//        )
//            ->where(function($query) use ($productId){
//                if($productId){
//                    $query->where('id' , $productId);
//                }
//            });
//
//        if($productId){
//            return $product->first();
//        }
//        else {
//            return $product->get();
//        }
//    }

    /**
     * @param $request
     * @param int|null $productId
     * @return bool|array|null
     */
    private function storeOrUpdate($request, int $productId = null): bool|array|null
    {
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

                foreach($validatedData['images'] as $image){
                    $product->addMedia(
                        storage_path('app/public/tmp/' . date('Y_m_d_H') . "/$image")
                    )
                        ->toMediaCollection('products');
                }
            }
            else {

                $product = Product::where('id', $productId)->first();
                if($product){
                    $storeImages = $validatedData['images'] ?? [];

                    foreach($storeImages as $image){
                        $product->addMedia(
                            storage_path('app/public/tmp/' . date('Y_m_d_H') . "/$image")
                        )
                        ->toMediaCollection('products');
                    }
                    $productImages = $product->getMedia('products');

                    $keptImages = array_merge(
                        $validatedData['keep_images'] ?? [] ,
                        $storeImages
                    );
                    unset($keptImages[0]);

                    foreach($productImages as $productImage){
                        if(!in_array($productImage->file_name , $keptImages)) {
                             $productImage->delete();
                        }
                    }

                    $product->update($validatedData);
                } else {
                    return false;
                }
            }

            return true;
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
            ->orderBy('id' , $orderBy)
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
