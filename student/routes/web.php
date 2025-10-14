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
Route::get("/student", [StudentController::class, "index"])->name('student');

Route::post("/student/create", [StudentController::class, "store"])->name('student.create.store');

Route::get("/student/addForm", [StudentController::class, "create"]);
Route::get("/student/{id}/edit", action: [StudentController::class, "edit"])->name('student.edit');

Route::put("/student/{id}", action: [StudentController::class, "update"])->name('student.update');


Route::delete("/student/{id}", action: [StudentController::class, "destroy"])->name('student.destroy');

Route::get('/test-success', function () {
    return redirect()->route('student')->with('success', 'Test message works!');
});