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
    Route::get('dummy', [UserController::class, 'dummy']);
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


