<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\NameWithIdResource;
use App\Models\Category;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use HttpResponse;

    /**
     * Display a listing of the resource.
     */
    public function parentCategoriesWithSubCategories(): JsonResponse
    {
        return $this->resourceResponse(
            CategoryResource::collection(
                Category::withCount('sub_categories')
                    ->with(['sub_categories' => fn($query) => $query->select(['id', 'name', 'parent_id'])])
                    ->where('parent_id' , '!=' , null)
                    ->get()
            )
        );
    }

    public function parentCategories(): JsonResponse
    {
        return $this->resourceResponse(NameWithIdResource::collection(Category::where('parent_id', null)->get())
        );
    }

    public function subCategories(int $id): JsonResponse
    {
        //TODO Fetch All Sub Categories With One Category
        return $this->resourceResponse(
            NameWithIdResource::collection(
                Category::where('parent_id' , $id)->get()
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeParentCategory(CategoryRequest $request): JsonResponse
    {
        $category = Category::where('parent_id' , null)
            ->where('name' , $request->name)
            ->first(['id']);

        if(!$category){
            $category = Category::create(['name' => $request->name]);

            return $this->createdResponse(new CategoryResource($category));
        }

        return $this->validationErrorsResponse(
            ['category' => translateErrorMessage('category' , 'exists')]
        );
    }


    public function storeSubCategory(SubCategoryRequest $request): JsonResponse
    {
        $errors = [];
        $subCategory = Category::where('name' , $request->name)
            ->where('parent_id' , '!=' , null)
            ->first();

        $parentIDExists = Category::where('id' , $request->parent_id)->where('parent_id' , null)->first(['id']);

        if(!$parentIDExists)$errors = translateErrorMessage('category' , 'not_found');
        if($subCategory) $errors['sub_category'] = translateErrorMessage('sub_category' , 'exists');

        if(!$errors)
        {
            $subCategory = Category::create($request->validated());

            return $this->createdResponse(new NameWithIdResource($subCategory));
        }

        if(isset($errors['sub_category'])){

            return $this->validationErrorsResponse($errors);
        }

        return $this->notFoundResponse($errors);
    }

    /**
     * Display the specified resource.
     */
    public function showParentCategory(int $id): JsonResponse
    {
        $category= Category::whereHas('sub_categories')
                    ->withCount('sub_categories')
                    ->with(['sub_categories' => fn($query) => $query->select(['id', 'name', 'parent_id'])])
                    ->where('parent_id' , null)
                    ->where('id' , $id)
                    ->first();

        if($category){

            return $this->resourceResponse(new CategoryResource($category));
        }

        return $this->notFoundResponse(translateErrorMessage('category' , 'not_found'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateParentCategory(CategoryRequest $request,int $id): JsonResponse
    {
        $category = Category::where('id' , $id)
            ->where('parent_id' , null)
            ->first();
        if($category)
        {
            //TODO Check If New Category Name Exists
            $nameExists = Category::where('name' , $request->name)
                ->where('id' , '!=' , $category->name)
                ->first(['name']);
            if(!$nameExists){
                $category->name = $request->name;
                $category->save();

                return $this->successResponse(
                    new NameWithIdResource($category),
                    translateSuccessMessage('category' , 'updated')
                );
            }

            return $this->validationErrorsResponse(
                ['category' => translateErrorMessage('category' , 'exists')]
            );
        }

        return $this->notFoundResponse(
            translateErrorMessage('category' , 'not_found')
        );
    }

    public function updateSubCategory(SubCategoryRequest $request , int $id): JsonResponse
    {
        //TODO Check If The Sub Category Exists Or Not
        $subCategory = Category::where('parent_id' , '!=' , null)
            ->where('parent_id' , $request->parent_id)
            ->where('id' , $id)
            ->first();

        if($subCategory){

            //TODO Check If The New Name Taken With Other Sub Category
            $nameExists = Category::where('parent_id' , '!=' , null)
                ->where('parent_id' , $request->parent_id)
                ->where('name' , $request->name)
                ->where('id' ,'!=', $id)
                ->first(['name']);

            if(!$nameExists){
                $subCategory->update(['name' => $request->name]);

                return $this->successResponse(
                    new NameWithIdResource($subCategory) ,
                    translateSuccessMessage('sub_category' , 'updated')
                );
            }

            return $this->validationErrorsResponse(
                ['sub_category' => translateErrorMessage('sub_category' , 'exists')]
            );
        }

        return $this->notFoundResponse(translateErrorMessage('sub_category' , 'not_found'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyParentCategory(int $id): JsonResponse
    {
        $category = Category::where('parent_id' , null)->where('id' , $id)->first();

        if($category){
            $category->delete();

            return $this->successResponse(msg:translateSuccessMessage('category' , 'deleted'));
        }

        return $this->notFoundResponse(translateErrorMessage('category' , 'not_found'));
    }

    public function destroySubCategory(int $id): JsonResponse
    {
        $subCategory = Category::where('parent_id' , '!=' , null)->where('id' , $id)->first();

        if($subCategory){
            $subCategory->delete();

            return $this->successResponse(msg:translateSuccessMessage('sub_category' , 'deleted'));
        }

        return $this->notFoundResponse(translateErrorMessage('sub_category' , 'not_found'));
    }
}
