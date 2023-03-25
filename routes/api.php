<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MeasurementUnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
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
//        Route::group(['prefix' => 'brands'] , function(){
//           Route::get('' , [BrandController::class , 'index']);
//           Route::get('{brand}' , [BrandController::class , ' show']);
//           Route::post('' , [BrandController::class , 'store']);
//           Route::post('{brand}' , [BrandController::class , 'update']);
//           Route::delete('{brand}' , [BrandController::class , 'destroy']);
//        });

    Route::apiResource('brands' , BrandController::class);

    // Attributes
        Route::apiResource('attributes', AttributeController::class);

    // Measurements Units
        Route::apiResource('units', MeasurementUnitController::class);
    // Profile
        Route::post('/profile', [ProfileController::class, 'index']);

    // Parent Categories
    Route::group(['prefix' => 'parent_categories'] , function(){
        Route::get('', [CategoryController::class, 'parentCategories']);
        Route::post('', [CategoryController::class, 'storeParentCategory']);
        Route::get('{id}', [CategoryController::class, 'showParentCategory'])
            ->whereNumber('id');
        Route::put('{id}', [CategoryController::class, 'updateParentCategory'])
            ->whereNumber('id');
        Route::delete('{id}', [CategoryController::class, 'destroyParentCategory'])
            ->whereNumber('id');
    });

        Route::group(['prefix' => 'sub_categories'] , function(){
            Route::post('', [CategoryController::class, 'storeSubCategory']);
            Route::get('{id}', [CategoryController::class, 'subCategories'])
                ->whereNumber('id');
            Route::put('{id}', [CategoryController::class, 'updateSubCategory'])
                ->whereNumber('id');
            Route::delete('{id}', [CategoryController::class, 'destroySubCategory'])
                ->whereNumber('id');
        });

    // Products
    Route::apiResource('products' , ProductController::class)
        ->whereNumber('product');
    // Services
        Route::apiResource('services' , ServiceController::class);

        Route::apiResource('projects' , ProjectController::class)->except(['update']);
});


Route::group(['prefix' => 'public'] , function(){
   // Products
    Route::group(['prefix' => 'products'] , function(){
        Route::get('' , [ProductController::class , 'index']);
        Route::get('{product}' , [ProductController::class , 'show'])
            ->whereNumber('product');
    });

    // Services
    Route::get('services' , [ServiceController::class , 'index']);

    Route::get('brands' , [BrandController::class , 'index']);

});
