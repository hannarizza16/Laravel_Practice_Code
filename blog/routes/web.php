<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    session(['foo' => 'bar']);
    return session('foo');
});

Route::get('/test2', function () {
    session(['foo' => 'bar']);
    return session('foo');
});