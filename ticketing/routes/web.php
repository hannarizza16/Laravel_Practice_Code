<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

// Route::get('/', function () {
//     return Inertia::render('welcome'); //load welcome.tsx
// })->name('home');


Route::get('/test-db', function () {
    $tickets = DB::table('tickets')->get();
    return response()->json($tickets);
});
