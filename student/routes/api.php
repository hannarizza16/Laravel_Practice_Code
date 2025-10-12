<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//get
Route::get("/student", [StudentController::class, "index"]);

Route::post("/student/create", [StudentController::class, "store"] );