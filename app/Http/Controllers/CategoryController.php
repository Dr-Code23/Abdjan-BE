<?php

namespace App\Http\Controllers;

use App\Actions\ChangeRecordStatus;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\ChangeRecordStatusRequest;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\NameWithIdResource;
use App\Models\Category;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
                Category::whereNull('parent_id')
                    ->where(function($query){
                        if(isPublicRoute()){
                            $query->where('status' , true);
                        }
                    })
                    ->get()
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
                Category::where('parent_id' , $id)
                    ->where(function($query){
                        if(isPublicRoute()){
                            $query->where('status' , true);
                        }
                    })
                    ->get()
            )
        );
    }

    /**
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function storeRootCategory(CategoryRequest $request): JsonResponse
    {
        $errors = [];
        checkIfNameExists(
            Category::class ,
            $request ,
            $errors ,
            parentId: ['=' , null]
        );

        if(!$errors){
            $inserted = Category::create($request->validated());

            if($inserted){

                return $this->createdResponse(
                    msg:translateSuccessMessage('parent_category' , 'created')
                );

            } else {

                return $this->error(
                    null,
                    ResponseAlias::HTTP_INTERNAL_SERVER_ERROR ,
                    translateWord('failed_to_insert')
                );
            }
        }

        return $this->validationErrorsResponse($errors);
    }

    /**
     * @param SubCategoryRequest $request
     * @return JsonResponse
     */
    public function storeDerivedCategory(SubCategoryRequest $request): JsonResponse
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
            Category::create($request->validated());

            return $this->createdResponse(
                msg:translateSuccessMessage('sub_category' , 'created')
            );
        }

        return $this->validationErrorsResponse($errors);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function showParentCategory(int $id): JsonResponse
    {
        $category= Category::whereNull('parent_id')
                    ->where('id' , $id)
                    ->first();

        if($category){
            return $this->resourceResponse(
                new NameWithIdResource($category , $category->getTranslations('name'))
            );
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
                    msg:translateSuccessMessage('category' , 'updated')
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
    public function updateDerivedCategory(SubCategoryRequest $request , int $id): JsonResponse
    {
        $errors = [];
        $data = $request->validated();

        //TODO Check If The Sub Category Exists Or Not
        $subCategory = Category::where('parent_id' , $data['parent_id'])
            ->where('id' , $id)
            ->first();

        if($subCategory){

            //TODO Check If The New Name Taken With Other Sub Category

            checkIfNameExists(
                Category::class ,
                $request ,
                $errors ,
                $id,
                parentId: ['=' , null]
            );

            if(!$errors){
                $subCategory->update(['name' => $data['name']]);

                return $this->successResponse(
                    msg:translateSuccessMessage('sub_category' , 'updated')
                );
            }

            return $this->validationErrorsResponse($errors);
        }

        return $this->notFoundResponse(
            translateErrorMessage('sub_category' , 'not_found')
        );
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroyParentCategory(int $id): JsonResponse
    {
        $category = Category::whereNull('parent_id')
            ->where('id' , $id)
            ->first();

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
    public function destroyDerivedCategory(int $id): JsonResponse
    {
        $subCategory = Category::where('id' , $id)
            ->whereNotNull('parent_id')
            ->first();

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
