<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/about', function () {
//     return "about"; // return string
// });


//get
Route::get("/student", [StudentController::class, "index"]);

// Route::post("/student/create", [StudentController::class, "store"] );