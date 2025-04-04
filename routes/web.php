<?php

use App\Http\Controllers\CMS\OrderController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\CMS\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('v1/login', [AuthController::class, 'login']);
Route::get('/login', function () {
    return view('Auth.Login');
})->name('login')->middleware('guest');

Route::middleware(['auth', 'web'])->group(function () {

    Route::get('/user', function () {
        return view('admin.user');
    });

    Route::get('/product', function () {
        return view('admin.product');
    });

    Route::get('/billing',  function () {
        return view('admin.billing');
    });

    Route::get('/home',  function () {
        return view('admin.dashboard');
    });

    Route::get('v1/order/{id}/invoice', [OrderController::class, 'showInvoice']);

    Route::post('v1/logout', [AuthController::class, 'logout']);
});
Route::prefix('v1')->group(function () {

    // route  api  //
    Route::prefix('product')->controller(ProductController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/search', 'search');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateDataById');
        Route::delete('/delete/{id}', 'deleteDataById');
    });
    Route::prefix('order')->controller(OrderController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/recommendations', 'getRecommendedProducts');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::get('/top-product', 'getTopProducts');
        Route::put('/{id}/status', 'updateStatus');
        Route::get('/today', 'getTodayOrders');
        Route::delete('/delete/{id}', 'deleteDataById');
    });
});
// ui web
Route::get('/',  function () {
    return view('web.home');
});
Route::get('/chart',  function () {
    return view('web.chart');
});

Route::get('/product-web', function () {
    return view('web.product');
});
Route::get('/result', function (Request $request) {
    $product_id = $request->query('product_id');
    $product_name = $request->query('product_name');
    return view('web.result', compact('product_id', 'product_name'));
});
