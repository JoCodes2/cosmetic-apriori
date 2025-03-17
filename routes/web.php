<?php

use Illuminate\Support\Facades\Route;



Route::get('/user', function () {
    return view('admin.user');
});

Route::get('/product', function () {
    return view('admin.product');
});
