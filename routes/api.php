<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangeStatusController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\GeneralExpenseController;
use App\Http\Controllers\MeasurementUnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectExpenseController;
use App\Http\Controllers\ProjectPaymentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaravelDaily\Invoices\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

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

    Route::apiResource('roles' , RoleController::class)
        ->middleware('permission:role_management');
    // Logout
        Route::post('logout' ,[ AuthController::class , 'logout']);
    // Users

        Route::group(['middleware' =>'permission:user_management'] , function(){
            Route::post('users/{user}' , [UserController::class , 'update'])
                ->whereNumber('user');

            Route::apiResource('users', UserController::class)
            ->except(['update']);
        });

    // Brands
        Route::group(['prefix' => 'brands' , 'middleware' => ['permission:brand_management']] , function(){
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
        Route::apiResource('attributes', AttributeController::class)
        ->middleware('attribute_management');

    // Measurements Units
        Route::apiResource('units', MeasurementUnitController::class)
        ->middleware('unit_management');
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

       Route::get('contact' , [ContactUsController::class , 'index'])
            ->middleware('permission:contact_us_management');

       Route::group(['prefix' => 'about_us' , 'middleware' => ['permission:about_us_management']] , function(){
          Route::get('' , [AboutUsController::class , 'show']);
          Route::post('' , [AboutUsController::class , 'update']);
       });

       Route::group(['prefix' => 'settings' , 'middleware' => ['permission:settings_management']] , function (){
           Route::get('' , [SettingController::class , 'show']);
           Route::post('' , [SettingController::class , 'update']);
       });
});


Route::group(['prefix' => 'public'] , function(){
   // Products
    Route::group(['prefix' => 'products'] , function(){
        Route::get('' , [ProductController::class , 'showAllForPublicUser']);
        Route::get('{product}' , [ProductController::class , 'show'])
            ->whereNumber('product');
    });

    // Services
    Route::group(['prefix' => 'services'] , function(){
        Route::get('' , [ServiceController::class , 'showAllServicesForPublicUser']);
        Route::get('{id}' , [ServiceController::class , 'show'])
            ->whereNumber('id');
    });

    // Brands
    Route::get('brands' , [BrandController::class , 'index']);

    // Categories
    Route::get('parent_categories' , [CategoryController::class , 'parentCategories']);
    Route::get('sub_categories/{id}' , [CategoryController::class , 'subCategories'])
        ->whereNumber('id');
    Route::get('category_with_children' , [CategoryController::class , 'getCategoryWithAllChildren']);

    Route::post('contact' , [ContactUsController::class , 'store']);

    Route::get('about_us' , [AboutUsController::class , 'show']);

    Route::get('settings' , [SettingController::class , 'show']);
});


Route::get('good' , function(){

    $customer = Invoice::makeParty([
        'name' => 'John Doe',
    ]);

    $item = Invoice::makeItem('Your service or product title')->pricePerUnit(9.99);

//    return Invoice::make()->buyer($customer)->addItem($item)->download();
//    return view('main');
    $pdf = Pdf::loadView('main' , ['name' => 'Simple Name'])->setPaper('a2');
    return $pdf->download();
});
