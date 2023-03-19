<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MeasurementUnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
    // Logout
        Route::post('logout' ,[ AuthController::class , 'logout']);
    // Users
        Route::apiResource('users', UserController::class);

    // Brands
        Route::apiResource('brands', BrandController::class);

    // Attributes
        Route::apiResource('attributes', AttributeController::class);

    // Measurements Units
        Route::apiResource('units', MeasurementUnitController::class);
    // Profile
        Route::post('/profile', [ProfileController::class, 'index']);

    // Categories
        Route::get('categories_with_sub_categories', [CategoryController::class, 'parentCategoriesWithSubCategories']);
        Route::get('parent_categories', [CategoryController::class, 'parentCategories']);
        Route::get('sub_categories/{id}', [CategoryController::class, 'subCategories'])
            ->whereNumber('id');
        Route::get('parent_categories/{id}', [CategoryController::class, 'showParentCategory'])->whereNumber('id');

        Route::post('parent_categories', [CategoryController::class, 'storeParentCategory']);

        Route::post('sub_categories', [CategoryController::class, 'storeSubCategory']);

        Route::put('parent_categories/{id}', [CategoryController::class, 'updateParentCategory'])
            ->whereNumber('id');

        Route::put('sub_categories/{id}', [CategoryController::class, 'updateSubCategory'])
            ->whereNumber('id');

        Route::delete('parent_categories/{id}', [CategoryController::class, 'destroyParentCategory'])
            ->whereNumber('id');
        Route::delete('sub_categories/{id}', [CategoryController::class, 'destroySubCategory'])
            ->whereNumber('id');
    // Products
        Route::apiResource('products' , ProductController::class);
//        Route::group(['prefix' => 'products'] , function(){
//            Route::get('' , [ProductController::class , 'index']);
//        });
});
