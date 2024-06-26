<?php

use Illuminate\Support\Facades\Route;
use App\Events\NewUserRegisteredEvent;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('send-mail', function () {
   
    // this is without job

    // dispatch(function(){
        
    // })->delay(now()->addSeconds(5));
        dispatch(new \App\Jobs\SendTestEmailJob())->delay(now()->addSeconds(5));
    echo "Email is Sent.";
    // this is without job
});

Route::get('event-list', function () {

    $user = [
        'user' => 'test event listener',
        'body' => 'This is for testing email using smtp'
    ];

   
    event(new NewUserRegisteredEvent($user));
});

use App\Http\Controllers\API\PaymentController;
Route::view('payment-page','payment');

Route::get('create-order', [PaymentController::class, 'createOrder']);
Route::post('verify-payment', [PaymentController::class, 'verifyPayment']);

Route::post('/api/create-order', [PaymentController::class, 'createOrder']);


