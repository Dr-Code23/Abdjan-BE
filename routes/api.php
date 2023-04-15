<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangeStatusController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\GeneralExpenseController;
use App\Http\Controllers\MeasurementUnitController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectExpenseController;
use App\Http\Controllers\ProjectPaymentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SelectMenuController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
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

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:api']], function () {

    Route::apiResource('roles' , RoleController::class)
        ->middleware('permission:role_management');
    // Logout
        Route::post('logout' ,[ AuthController::class , 'logout'])->name('logout');
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
           Route::get('{brand}' , [BrandController::class , 'show']);
           Route::post('' , [BrandController::class , 'store']);

           Route::post('{brand}' , [BrandController::class , 'update'])
            ->whereNumber('brand');

           Route::delete('{brand}' , [BrandController::class , 'destroy'])
            ->whereNumber('brand');
        });

    // Attributes
        Route::apiResource('attributes', AttributeController::class)
        ->middleware('permission:attribute_management');

    // Measurements Units
        Route::apiResource('units', MeasurementUnitController::class)
        ->middleware('permission:unit_management');

    // Profile
        Route::post('profile', [ProfileController::class, 'index']);
        Route::get('profile' , [ProfileController::class , 'showProfileInfo']);
        Route::group(['middleware' => 'permission:category_management'] , function(){

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

                Route::get('{parentCategory}/{subCategory}' , [CategoryController::class , 'showDerivedCategory'])
                    ->whereNumber(['parentCategory' , 'subCategory']);
                Route::post('', [CategoryController::class, 'storeDerivedCategory']);
                Route::get('{id}', [CategoryController::class, 'subCategories'])
                    ->whereNumber('id');

                Route::put('{id}/{subCategoryId}', [CategoryController::class, 'updateDerivedCategory'])
                    ->whereNumber('id')
                    ->whereNumber('subCategoryId');

                Route::delete('{id}', [CategoryController::class, 'destroyDerivedCategory'])
                    ->whereNumber('id');
            });
    });

    // Change item Status
    Route::put('change_status/{type}/{id}' , [ChangeStatusController::class , 'handle'])
        ->whereAlpha('type')
        ->whereNumber('id');

    // Products
    Route::apiResource('products' , ProductController::class)
        ->middleware('permission:product_management')
        ->whereNumber('product');

    // Services
        Route::apiResource('services' , ServiceController::class)
        ->middleware('permission:service_management');

    // Projects
    Route::apiResource('projects' , ProjectController::class)
        ->except(['update'])
        ->middleware('permission:project_management');

    // Project Payments
    Route::group(['prefix' => 'project_payments' , 'middleware' => ['permission:project_payment_management']] , function(){
        Route::get('' , [ProjectPaymentController::class , 'index']);
        Route::post('' , [ProjectPaymentController::class , 'store']);
        Route::get('{project}' , [ProjectPaymentController::class , 'show'])
            ->whereNumber('project');

        Route::put('{projectPayment}' , [ProjectPaymentController::class , 'update'])
            ->whereNumber('projectPayment');

        Route::delete('{projectPayment}' , [ProjectPaymentController::class , 'destroy']);

        Route::get('payments/{projectId}' , [ProjectPaymentController::class , 'showAllPayments'])
            ->whereNumber('projectId');
    });

    // Project Expenses
    Route::group(['prefix' => 'project_expenses' , 'middleware' => ['permission:project_expenses_management']] , function(){
        Route::get('' , [ProjectExpenseController::class , 'index']);
        Route::get('{project}' , [ProjectExpenseController::class , 'show'])
            ->whereNumber('project');

        Route::post('' , [ProjectExpenseController::class , 'store']);
    });

    // General Expenses

    Route::apiResource('general_expenses' , GeneralExpenseController::class)
        ->middleware('permission:general_expenses_management');

    // Upload File
    Route::post('upload' , [FileManagerController::class , 'uploadTemporaryImage']);

        // Contact Us
       Route::get('contact' , [ContactUsController::class , 'index'])
            ->middleware('permission:contact_us_management');

       // About Us
       Route::group(['prefix' => 'about_us' , 'middleware' => ['permission:about_us_management']] , function(){
          Route::get('' , [AboutUsController::class , 'show']);
          Route::post('' , [AboutUsController::class , 'update']);
       });

       // Settings
       Route::group(['prefix' => 'settings' , 'middleware' => ['permission:settings_management']] , function (){
           Route::get('' , [SettingController::class , 'show']);
           Route::post('' , [SettingController::class , 'update']);
       });

       // Ads
       Route::group(['prefix' => 'ads' , 'middleware' => ['permission:ad_management']] , function(){
           Route::get('', [AdController::class , 'index']);
           Route::get('{ad}', [AdController::class , 'show']);
           Route::post('{ad}', [AdController::class , 'update']);
           Route::post('', [AdController::class , 'store']);
           Route::delete('{ad}' , [AdController::class , 'destroy']);
       });

       // Select Menu
       Route::group(['prefix' =>'select_menu'] , function(){
           Route::get('brands' , [SelectMenuController::class , 'brands']);
           Route::get('roles' , [SelectMenuController::class , 'roles']);
           Route::get('parent_categories' ,[SelectMenuController::class , 'parentCategories']);
           Route::get('attributes' , [SelectMenuController::class , 'attributes']);
           Route::get('units' , [SelectMenuController::class , 'units']);
           Route::get('sub_categories/{parentCategory}' , [SelectMenuController::class , 'subCategories'])
            ->whereNumber('parentCategory');
           Route::get('all_categories' , [SelectMenuController::class , 'allCategories']);
           Route::get('permissions' , [SelectMenuController::class , 'permissions']);

           Route::get('projects' , [SelectMenuController::class , 'projects']);

           Route::get('products' , [SelectMenuController::class , 'products']);
       });

       Route::get('dashboard' , [DashboardController::class , 'index']);

       Route::group(['prefix' => 'invoices'] , function(){
          Route::get('project_expenses/{projectExpense}' , [InvoiceController::class , 'projectExpenses'])
            ->middleware('permission:project_expenses_management');
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

    Route::get('ads' , [AdController::class , 'index']);
});
