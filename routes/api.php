<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    
Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post('create-user', [UserController::class, 'createUser']);
    Route::post('update-user', [UserController::class, 'updateUser']);
    // Route::get('dummy', [UserController::class, 'dummy']);
    Route::post('create-category', [ProductsController::class, 'createCategory']);
    Route::post('update-category', [ProductsController::class, 'updateCategory']);
    Route::post('create-product', [ProductsController::class, 'createProduct']);
    Route::post('update-product', [ProductsController::class, 'updateProduct']);
    Route::post('create-request-type',[ProductsController::class,'createRequestType']);
    Route::post('wish-cart',[ProductsController::class,'wishCart']);
    Route::get('get-user',[UserController::class,'getUser']);
    Route::post('cart-order',[ProductsController::class,'cartOrder']);
    Route::get('get-products',[ProductsController::class,'getProducts']);
});

Route::get('dummy', [UserController::class, 'dummy']);
Route::post('login', [UserController::class, 'login']);
Route::get('get-all-products',[ProductsController::class,'getAllProducts']);
Route::get('product-loadmore',[ProductsController::class,'productLoadMore']);
Route::get('category-product',[ProductsController::class,'getCategoryByProduct']);
Route::get('user-settings',[UserController::class,'getUserSettings']);




Route::get('check-name-route', [ProductsController::class, 'checkNameRoute']);

Route::get('test-name-route', [ProductsController::class, 'testNameRoute'])->name('testNameRoute');

Route::get('test-service-provider', [TestController::class, 'doService']);

Route::get('test-service-provider-two', [TestController::class, 'index']);

Route::get('test-service-getUserDetails/{userid}', [TestController::class, 'getUserDetails']);

Route::get('test-events-listeners', [TestController::class, 'store']);

Route::get('test-jobsqueues', [UserController::class, 'storeJobsQueues']);

Route::get('test-transaction', [UserController::class, 'testTransaction']);

Route::get('test-accessors', [UserController::class, 'getUsers']);

Route::get('users-with-settings',[UserController::class,'usersWithSettings']); // one to one

Route::get('settings-with-users',[UserController::class,'settingsWithUsers']); // one to one inverse

Route::get('product-categories-products',[TestController::class,'productCategoriesProducts']); // one to many

Route::get('products-with-categories',[TestController::class,'productsWithCategories']); // one to many inverse 

Route::get('save-many-product',[TestController::class,'saveManyProduct']);

Route::get('users-product-manytomany',[TestController::class,'usersProducts']);

Route::get('users-product-manytomany-inverse',[TestController::class,'productsUsers']);// many to many inverse 

Route::get('create-users-product-manytomany-attach',[TestController::class,'createProductManyToManyAttach']);// many to many inverse 

// revice laravel

Route::get('test-named-route',[TestController::class,'testNamedRoute'])->name('testnamedroute');

Route::get('get-named-route',[TestController::class,'getDataNamedRoute']);

// Route::get('profile', [TestController::class, 'showProfile'])->middleware('checkage');

Route::middleware(['checkage'])->group(function () {
    Route::get('profile', [TestController::class, 'showProfile']);
});

Route::get('get-provider', [TestController::class, 'getProvider']);