<?php

namespace App\Services;

use App\Exceptions\ModelExistsException;
use App\Models\Product;
use App\Models\Translations\ProductTranslation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService
{
    /**
     * @return Collection|array
     */
    public function index(): Collection|array
    {
        return $this->getUserWithSingleTranslation();
    }

    public function show($product): Model|Builder|null
    {
        return Product::with(
            [
                'translations' => fn($query) => $query->select(
                    [
                        'id',
                        'title',
                        'description',
                        'product_translations.product_id',
                        'locale'
                    ]
                )

                ,
                'brand',
                'attribute',
                'unit',
                'category' => fn($query) => $query->select(['id', 'name'])
            ]
        )
            ->where('id', $product)
            ->first();
    }

    /**
     * @param $request
     * @return Model|Collection|Builder|array|null
     *
     * @throws ModelExistsException
     */
    public function store($request): Model|Collection|Builder|array|null
    {
        return $this->storeOrUpdate($request);
    }

    /**
     * @throws ModelExistsException
     */
    public function update($request , $product): Model|Collection|Builder|array|null
    {
        return $this->storeOrUpdate($request , $product->id);
    }

    private function getUserWithSingleTranslation(int $productId = null): Model|Collection|Builder|array|null
    {
        $product = Product::with(
            [
                'translation' => function ($query) {
                    $query->select(['id', 'title', 'description', 'product_translations.product_id']);
                    $query->where('locale', app()->getLocale());
                },
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
     * @throws ModelExistsException
     */
    private function storeOrUpdate($request, int $productId = null): Model|Collection|Builder|array|null
    {
        $allTitles = [];
        foreach(config('translatable.locales') as $locale){
            if($request->has('title:'.$locale) && $request->input('title:'.$locale)){
                $allTitles[] = $request->input('title:'.$locale);
            }
        }

        $titleExists = ProductTranslation::whereIn('title' , $allTitles)
            ->where(function($query) use ($productId){
                if($productId){
                    info('Hi Update');
                    $query->where('product_id' ,'!=', $productId);
                }
            })
            ->first(['id' , 'title']);

        info($titleExists);
        if(!$titleExists)
        {
            if(!$productId) {
                $product = Product::create($request->validated());
            }

            return $this->getUserWithSingleTranslation($productId ?: $product->id);
        }

        throw new ModelNotFoundException(
            translateErrorMessage('title' , 'exists')
        );
    }
}
