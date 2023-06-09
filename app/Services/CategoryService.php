<?php

namespace App\Services;

use App\Facades\Search;
use App\Http\Controllers\CategoryController;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CategoryService
{

    public function getRootCategories()
    {
        $parentCategories =  Category::whereNull('parent_id')
            ->with('images')
            ->where(function($query){
                if(isPublicRoute()){
                    $query->where('status' , true);
                }
            })
            ->where(function($query){
                Search::searchForHandle(
                    $query ,
                    ['name'] ,
                    request('handle'),
                    ['name']
                );
            })
            ->latest('id');
        if(isPublicRoute()){
            $parentCategories = $parentCategories->get();
        }else {
            $parentCategories = $parentCategories->paginate(paginationCountPerPage());
        }

        return $parentCategories;
    }

    public function subCategories(int $id)
    {
        $subCategories =  Category::where('parent_id' , $id)
            ->where(function($query){
                if(isPublicRoute()){
                    $query->where('status' , true);
                }
            })
            ->latest('id');
        if(isPublicRoute()){
            $subCategories = $subCategories->get();
        }
        else {
            $subCategories = $subCategories->paginate(paginationCountPerPage());
        }
            return $subCategories;
    }

    /**
     * @param CategoryRequest $request
     * @return bool|array
     */
    public function storeRootCategory(CategoryRequest $request): bool|array
    {
        $errors = [];

        checkIfNameExists(
            Category::class ,
            $request ,
            $errors ,
            parentId: ['=' , null]
        );

        if(!$errors){
            $category = Category::create($request->validated());
            $category
                ->addMediaFromRequest('img')
                ->usingFileName(Str::random().'.png')
                ->toMediaCollection(CategoryController::$categoriesCollectionName);

            return true;
        }

        return $errors;
    }

    /**
     * @param $request
     * @return array|bool
     */
    public function storeDerivedCategory($request): bool|array
    {
        $errors = [];
        $data = $request->validated();

        checkIfNameExists(
            Category::class ,
            $request ,
            $errors ,
            parentId: ['=' , $data['parent_id']]
        );


        $parentIDExists = Category::where('id' , $data['parent_id'])->first(['id']);

        if(!$parentIDExists) {
            $errors['parent_id'] = translateErrorMessage('category', 'not_found');
        }

        if(!$errors)
        {
            Category::create($data);

            return true;
        }
        return $errors;

    }

    public function updateRootCategory(CategoryRequest $request , int $id): bool|array
    {
        $fileOperationService = new FileOperationService();

        $errors = [];
        $category = Category::where('id' , $id)
            ->whereNull('parent_id')
            ->first();

        if($category)
        {
            //TODO Check If New Category Name Exists
            checkIfNameExists(
                Category::class ,
                $request ,
                $errors ,
                $id,
                parentId: ['=' , null]
            );

            if(!$errors){
                $category->update($request->validated());
                if($request->hasFile('img')){
                    $categoryImage = $category->getFirstMedia(
                        CategoryController::$categoriesCollectionName
                    );

                    if($categoryImage){
                        $categoryImage->delete();
                    }

                    $fileOperationService->storeImageFromRequest(
                        $category,
                        CategoryController::$categoriesCollectionName,
                    );
                }

                return true;
            }

            return $errors;
        }

        return false;
    }

    /**
     * @return Collection|array
     */
    public function getCategoryWithAllChildren(): Collection|array
    {
        return Category::with(
            [
                'sub_categories' => function($query){
                    $query->select(['id' , 'parent_id' , 'name']);
                    $query->with(['sub_sub_categories' => fn($query) => $query->select(['id' , 'parent_id' , 'name'])]);
                },
                'images' => fn($query) => $query->limit(1)
            ]
        )
            ->whereNull('parent_id')
            ->limit(10)
            ->get([
                'id',
                'parent_id',
                'img',
                'name'
            ]);
    }
}
