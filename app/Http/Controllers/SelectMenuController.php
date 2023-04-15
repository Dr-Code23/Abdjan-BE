<?php

namespace App\Http\Controllers;

use App\Http\Resources\NameWithIdResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SelectMenuResource;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\MeasureUnit;
use App\Models\Product;
use App\Models\Project;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SelectMenuController extends Controller
{
    use HttpResponse;
    public function brands(){
        return NameWithIdResource::collection(Brand::where('status' , true)->get(['id' , 'name']));
    }

    public function roles(): JsonResponse
    {
        return $this->resourceResponse(
            Role::where('name' , '!=' , 'super_admin')->get(['id' , 'name'])
        );
    }

    public function parentCategories(): JsonResponse
    {
        return $this->resourceResponse(
            NameWithIdResource::collection(Category::whereNull('parent_id')->get())
        );
    }

    public function units(): JsonResponse
    {
        return $this->resourceResponse(
            NameWithIdResource::collection(MeasureUnit::all(['id' , 'name']))
        );
    }

    public function attributes(): JsonResponse
    {
        return $this->resourceResponse(
            NameWithIdResource::collection(Attribute::all(['id' , 'name']))
        );
    }

    public function subCategories(int $parentCategory): JsonResponse
    {
        return $this->resourceResponse(
            NameWithIdResource::collection(Category::where('parent_id' , $parentCategory)->get(['id' , 'name']))
        );
    }

    public function permissions(): JsonResponse
    {
        $permissions = Permission::all(['id' , 'name']);
        foreach($permissions as $permission){
            $permission->name = translateWord($permission->name);
        }
        return $this->resourceResponse(
            NameWithIdResource::collection($permissions)
        );
    }

    public function projects(){
        return $this->resourceResponse(
            NameWithIdResource::collection(Project::all(['id' , 'project_name as name']))
        );
    }

    public function products(){
        return SelectMenuResource::collection(
            Product::all(['id' , 'name' , 'unit_price' , 'quantity'])
        );
    }

    public function allCategories(): JsonResponse
    {
        return $this->resourceResponse(
            NameWithIdResource::collection(Category::all(['id' , 'name']))
        );
    }
}
