<?php

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
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
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
    Route::post('/store', 'OrderController@store')->name('order.store');
    Route::post('/process/{unique_id}', 'PaymentController@processOrder');
    Route::post('/confirm/{confirmation_code}', 'PaymentController@confirmPayment');
});
