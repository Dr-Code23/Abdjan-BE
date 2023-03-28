<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangeStatusController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\GeneralExpenseController;
use App\Http\Controllers\MeasurementUnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectExpenseController;
use App\Http\Controllers\ProjectPaymentController;
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
        Route::group(['prefix' => 'brands'] , function(){
           Route::get('' , [BrandController::class , 'index']);
           Route::post('' , [BrandController::class , 'store']);
           Route::get('{brand}' , [BrandController::class , ' show'])
            ->whereNumber('brand');

           Route::post('{brand}' , [BrandController::class , 'update'])
            ->whereNumber('brand');

           Route::delete('{brand}' , [BrandController::class , 'destroy'])
            ->whereNumber('brand');
        });

    // Attributes
        Route::apiResource('attributes', AttributeController::class);

    // Measurements Units
        Route::apiResource('units', MeasurementUnitController::class);
    // Profile
        Route::post('profile', [ProfileController::class, 'index']);

    // Parent Categories
    Route::group(['prefix' => 'parent_categories'] , function(){

        Route::get('', [CategoryController::class, 'parentCategories']);
        Route::post('', [CategoryController::class, 'storeRootCategory']);
        Route::get('{id}', [CategoryController::class, 'showParentCategory'])
            ->whereNumber('id');

        Route::post('{id}', [CategoryController::class, 'updateParentCategory'])
            ->whereNumber('id');

        Route::delete('{id}', [CategoryController::class, 'destroyParentCategory'])
            ->whereNumber('id');
    });

    Route::group(['prefix' => 'sub_categories'] , function(){

        Route::post('', [CategoryController::class, 'storeDerivedCategory']);
        Route::get('{id}', [CategoryController::class, 'subCategories'])
            ->whereNumber('id');

        Route::put('{id}', [CategoryController::class, 'updateDerivedCategory'])
            ->whereNumber('id');

        Route::delete('{id}', [CategoryController::class, 'destroyDerivedCategory'])
            ->whereNumber('id');
    });


    Route::put('change_status/{type}/{id}' , [ChangeStatusController::class , 'handle'])
        ->whereAlpha('type')
        ->whereNumber('id');

    // Products
    Route::apiResource('products' , ProductController::class)
        ->whereNumber('product');

    // Services
        Route::apiResource('services' , ServiceController::class);

        // Projects
    Route::apiResource('projects' , ProjectController::class)->except(['update']);

    // Project Payments
    Route::group(['prefix' => 'project_payments'] , function(){
        Route::get('' , [ProjectPaymentController::class , 'index']);
        Route::post('' , [ProjectPaymentController::class , 'store']);
        Route::get('{project}' , [ProjectPaymentController::class , 'show'])
            ->whereNumber('project');

        Route::put('{projectPayment}' , [ProjectPaymentController::class , 'update'])
            ->whereNumber('projectPayment');

        Route::delete('{projectPayment}' , [ProjectPaymentController::class , 'destroy']);
    });

    // Project Expenses
    Route::group(['prefix' => 'project_expenses'] , function(){
        Route::get('' , [ProjectExpenseController::class , 'index']);
        Route::get('{project}' , [ProjectExpenseController::class , 'show'])
            ->whereNumber('project');

        Route::post('' , [ProjectExpenseController::class , 'store']);
    });

    // General Expenses

    Route::apiResource('general_expenses' , GeneralExpenseController::class);

    Route::post('upload' , [FileManagerController::class , 'uploadTemporaryImage']);

});


Route::group(['prefix' => 'public'] , function(){
   // Products
    Route::group(['prefix' => 'products'] , function(){
        Route::get('' , [ProductController::class , 'showAllForPublicUser']);
        Route::get('{product}' , [ProductController::class , 'show'])
            ->whereNumber('product');
    });

    // Services
    Route::get('services' , [ServiceController::class , 'index']);

    // Brands
    Route::get('brands' , [BrandController::class , 'index']);

    // Categories
    Route::get('parent_categories' , [CategoryController::class , 'parentCategories']);
    Route::get('sub_categories/{id}' , [CategoryController::class , 'subCategories'])
        ->whereNumber('id');

});

