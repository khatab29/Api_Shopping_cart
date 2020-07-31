<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth',
    'namespace' => 'Auth\User'

], function ($router) {

    Route::post('register', 'UserAuthController@register')->name('user.register');
    Route::post('login', 'UserAuthController@login')->name('user.login');
    Route::post('logout', 'UserAuthController@logout')->name('user.logout');
    Route::post('refresh', 'UserAuthController@refresh')->name('user.refresh.token');
    Route::get('me', 'UserAuthController@me')->name('user.profile');
});

Route::group([

    
    'prefix' => 'auth/supplier',
    'namespace' => 'Auth\Supplier'
], function ($router) {

    Route::post('register', 'SupplierAuthController@register')->name('supplier.register');
    Route::post('login', 'SupplierAuthController@login')->name('supplier.login');
    Route::post('logout', 'SupplierAuthController@logout')->name('supplier.logout');
    Route::post('refresh', 'SupplierAuthController@refresh')->name('supplier.refresh.token');
    Route::get('me', 'SupplierAuthController@me')->name('supplier.profile');
});



Route::group(['prefix' => 'products'], function () {
    Route::get('/', 'ProductController@index')->name('products.index');
    Route::get('/{product}', 'ProductController@show')->name('products.show');
    Route::delete('/{product}/delete', 'ProductController@destroy')->name('products.destroy');
    Route::post('store', 'ProductController@store')->name('products.store');
    Route::put('{product}/update', 'ProductController@update')->name('products.update');
});

Route::group(['prefix' => 'cart'], function () {
    Route::get('/token', 'CartController@genrateToken')->name('cart.token');
    Route::post('/{product_id}', 'CartController@addToCart')->name('cart.add_product');
    Route::get('/checkout', 'CartController@checkout')->name('cart.checkout');
});

Route::group(['prefix' => 'order'], function () {
    Route::post('/store', 'OrderController@store')->name('order.store')->name('order.store');
    Route::post('/process/{unique_id}', 'PaymentController@processOrder')->name('patment.process');
    Route::post('/confirm/{confirmation_code}', 'PaymentController@confirmPayment')->name('payment.confirm');
    Route::post('/cancel/{unique_id}', 'OrderController@destroy')->name('order.cancel');
});
