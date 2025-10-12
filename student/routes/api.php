<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//get
Route::get("/student", [StudentController::class, "index"]);
Route::get("/student/{id}", [StudentController::class, "show"]);
Route::post("/student/create", [StudentController::class, "store"]);
Route::delete("/student/{id}", [StudentController::class, "destroy"]);
