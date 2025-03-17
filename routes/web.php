<?php

use App\Http\Controllers\CMS\ProductController;
use Illuminate\Support\Facades\Route;



Route::get('/user', function () {
    return view('admin.user');
});

Route::get('/product', function () {
    return view('admin.product');
});

Route::get('/',  function () {
    return view('admin.dashboard');
});


// route  api  //
Route::prefix('v1/product')->controller(ProductController::class)->group(function () {
    Route::get('/', 'getAllData');
    Route::post('/create', 'createData');
    Route::get('/get/{id}', 'getDataById');
    Route::post('/update/{id}', 'updateDataById');
    Route::delete('/delete/{id}', 'deleteDataById');
});
