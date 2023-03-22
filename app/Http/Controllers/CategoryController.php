<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\NameWithIdResource;
use App\Models\Category;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    use HttpResponse;

    /**
     * @return JsonResponse
     */
    public function parentCategories(): JsonResponse
    {
        return $this->resourceResponse(
            NameWithIdResource::collection(
                Category::whereNull('parent_id')->get()
            )
        );
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
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
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function storeParentCategory(CategoryRequest $request): JsonResponse
    {
        $errors = [];
        checkIfNameExists(Category::class , $request , $errors , parentId: ['=' , null]);

        if(!$errors){
            $category = Category::create($request->validated());

            return $this->createdResponse(new CategoryResource($category));
        }

        return $this->validationErrorsResponse($errors);
    }


    /**
     * @param SubCategoryRequest $request
     * @return JsonResponse
     */
    public function storeSubCategory(SubCategoryRequest $request): JsonResponse
    {
        $errors = [];

        checkIfNameExists(Category::class , $request , $errors , parentId: ['!=' , null]);
        $parentIDExists = Category::where('id' , $request->parent_id)->whereNull('parent_id')->first(['id']);

        if(!$parentIDExists) {
            $errors['parent_id'] = translateErrorMessage('category', 'not_found');
        }

        if(!$errors)
        {
            $subCategory = Category::create($request->validated());

            return $this->createdResponse(new NameWithIdResource($subCategory));
        }

        return $this->validationErrorsResponse($errors);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function showParentCategory(int $id): JsonResponse
    {
        $category= Category::whereHas('sub_categories')
                    ->with(['sub_categories' => fn($query) => $query->select(['id', 'name', 'parent_id'])])
                    ->whereNull('parent_id')
                    ->where('id' , $id)
                    ->first();

        if($category){
            $subCategories = [];
            foreach($category->sub_categories as $subCategory){
                $subCategory->name = $subCategory->getTranslations('name');
                unset($subCategory->parent_id);
                $subCategories[] = $subCategory;
            }

            return $this->resourceResponse([
                'id' => $category->id,
                'name' => $category->getTranslations('name'),
                'sub_categories' => $subCategories
            ]);
        }

        return $this->notFoundResponse(
            translateErrorMessage('category' , 'not_found')
        );
    }

    /**
     * @param CategoryRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateParentCategory(CategoryRequest $request, int $id): JsonResponse
    {
        $errors = [];
        $category = Category::where('id' , $id)
            ->whereNull('parent_id')
            ->first();

        if($category)
        {
            //TODO Check If New Category Name Exists
            checkIfNameExists(Category::class , $request , $errors ,$id, parentId: ['=' , null] );
            if(!$errors){
                $category->update($request->validated());

                return $this->successResponse(
                    new NameWithIdResource($category),
                    translateSuccessMessage('category' , 'updated')
                );
            }

            return $this->validationErrorsResponse($errors);
        }

        return $this->notFoundResponse(
            translateErrorMessage('category' , 'not_found')
        );
    }

    /**
     * @param SubCategoryRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateSubCategory(SubCategoryRequest $request , int $id): JsonResponse
    {
        $errors = [];
        //TODO Check If The Sub Category Exists Or Not
        $subCategory = Category::whereNotNull('parent_id')
            ->where('id' , $id)
            ->first();

        if($subCategory){

            //TODO Check If The New Name Taken With Other Sub Category

            checkIfNameExists(Category::class , $request , $errors ,$id, parentId: ['!=' , null]);

            if(!$errors){
                $subCategory->update(['name' => $request->name]);

                return $this->successResponse(
                    new NameWithIdResource($subCategory) ,
                    translateSuccessMessage('sub_category' , 'updated')
                );
            }

            return $this->validationErrorsResponse($errors);
        }

        return $this->notFoundResponse(translateErrorMessage('sub_category' , 'not_found'));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroyParentCategory(int $id): JsonResponse
    {
        $category = Category::whereNull('parent_id')->where('id' , $id)->first();

        if($category){
            $category->delete();

            return $this->successResponse(
                msg:translateSuccessMessage('category' , 'deleted')
            );
        }

        return $this->notFoundResponse(
            translateErrorMessage('category' , 'not_found')
        );
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroySubCategory(int $id): JsonResponse
    {
        $subCategory = Category::whereNotNull('parent_id')->where('id' , $id)->first();

        if($subCategory){
            $subCategory->delete();

            return $this->successResponse(
                msg:translateSuccessMessage('sub_category' , 'deleted')
            );
        }

        return $this->notFoundResponse(
            translateErrorMessage('sub_category' , 'not_found')
        );
    }
}
