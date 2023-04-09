<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\NameWithIdResource;
use App\Interfaces\HasStatusColumn;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller implements HasStatusColumn
{
    use HttpResponse;
    public static string $categoriesCollectionName = 'categories';

    public function __construct(private readonly CategoryService $categoryService)
    {

    }

    public function parentCategories(): AnonymousResourceCollection
    {
        $parentCategories = $this->categoryService->getRootCategories();

        return CategoryResource::collection($parentCategories);

    }

    public function subCategories(int $id): AnonymousResourceCollection
    {
        //TODO Fetch All Sub Categories Associated With One Category

        return CategoryResource::collection( $this->categoryService->subCategories($id));

    }

    /**
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function storeRootCategory(CategoryRequest $request): JsonResponse
    {

        $result = $this->categoryService->storeRootCategory($request);

        if(is_bool($result) && $result){
            return $this->createdResponse(
                msg:translateSuccessMessage('category' , 'created')
            );
        }

        return $this->validationErrorsResponse($result);
    }

    /**
     * @param SubCategoryRequest $request
     * @return JsonResponse
     */
    public function storeDerivedCategory(SubCategoryRequest $request): JsonResponse
    {
        $result = $this->categoryService->storeDerivedCategory($request);

        if(is_bool($result) && $result){
            return $this->createdResponse(
                msg:translateSuccessMessage('category' , 'created')
            );
        } else if (is_bool($result)){

            return $this->error(
                null ,
                Response::HTTP_INTERNAL_SERVER_ERROR,
                msg:translateErrorMessage('category' , 'insert_failed'),
            );
        }

        return $this->validationErrorsResponse($result);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function showParentCategory(int $id): JsonResponse
    {
        $category= Category::whereNull('parent_id')
                    ->with('images')
                    ->where('id' , $id)
                    ->first();

        if($category){
            return $this->resourceResponse(
                new CategoryResource(
                    $category , ['name' => $category->getTranslations('name')]
                )
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
        $result = $this->categoryService->updateRootCategory($request , $id);

        if(is_bool($result) && $result){
            return $this->successResponse(
              msg:translateSuccessMessage('category' , 'updated')
            );

        } else if (is_bool($result)){
            return $this->notFoundResponse(
                translateErrorMessage('category' , 'not_found')
            );
        }

        return $this->validationErrorsResponse($result);
    }

    public function updateDerivedCategory(SubCategoryRequest $request , int $id , int $subCategoryId): JsonResponse
    {
        $errors = [];
        $data = $request->validated();

        //TODO Check If The Sub Category Exists Or Not
        $subCategory = Category::where('parent_id' , $subCategoryId)
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

    /**
     * @return JsonResponse
     */
    public function getCategoryWithAllChildren(): JsonResponse
    {
        return $this->resourceResponse(
            CategoryResource::collection(
                $this->categoryService->getCategoryWithAllChildren()
            )
        );
    }

    public function showDerivedCategory(int $parentCategory , int $subCategory){
        $category = Category::where('parent_id' , $parentCategory)->where('id' , $subCategory)->first();

        if($category) {
            return $this->resourceResponse(
                new NameWithIdResource($category,  $category->getTranslations('name'))
            );
        }

        return $this->notFoundResponse(translateErrorMessage('sub_category' , 'not_found'));
    }
}
