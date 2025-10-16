<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;



//get
Route::get("/student", [StudentController::class, "index"]);

Route::post("/student/create", [StudentController::class, "store"] );