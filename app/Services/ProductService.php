<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductService
{
    /**
     * @return Collection|array
     */
    public function index(): Collection|array
    {
        return $this->getProductWithSingleTranslation();
    }

    public function show($product): Builder|Model|null
    {
        $product= Product::with(
            [
                'brand',
                'attribute',
                'unit',
                'category' => fn($query) => $query->select(['id', 'name'])
            ]
        )
            ->where('id', $product)
            ->first();


        if($product){

            return $product;
//            if($publicUser){
//                $product->description = $product->getTranslations('description');
//                $product->name = $product->getTranslations('name');
//
//                return [
//                    'id' => $product->id,
//                    'name' => $product->getTranslations('name'),
//                    'description' => $product->getTranslations('description'),
//                    'category' => [
//                        'id' => $product->category->id,
//                        'name' => $product->category->name,
//                    ],
//                    'brand' => [
//                        'id' => $product->brand->id,
//                        'name' => $product->brand->name
//                    ],
//                    'attribute' => [
//                        'id' => $product->attribute->id,
//                        'name' => $product->attribute->name,
//                    ],
//                    'unit' => [
//                        'id' => $product->unit->id,
//                        'name' => $product->unit->name
//                    ],
//                ];
//            }
//            else {
//            }
        }

        return null;
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

    private function getProductWithSingleTranslation(int $productId = null): Model|Collection|Builder|array|null
    {
        $product = Product::with(
            [
                'brand',
                'attribute',
                'unit',
                'category' => fn($query) => $query->select(['id', 'name'])
            ]
        )
            ->where(function($query) use ($productId){
                if($productId){
                    $query->where('id' , $productId);
                }
            });

        if($productId){
            return $product->first();
        }
        else {
            return $product->get();
        }
    }

    /**
     * @param $request
     * @param int|null $productId
     * @return Model|Collection|Builder|array|null
     */
    private function storeOrUpdate($request, int $productId = null): Model|Collection|Builder|array|null
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
            }
            else {
                $product = Product::where('id', $productId)->first();

                $product->update($validatedData);
            }
            return $this->getProductWithSingleTranslation($productId ?: $product->id);
        }

        return $errors;
    }
}
