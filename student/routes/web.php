<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;



Route::get("/", [StudentController::class, "index"])->name('student');

Route::post("/student/create", [StudentController::class, "store"])->name('student.create.store');

Route::get("/student/addForm", [StudentController::class, "create"]);

Route::delete("/student/{id}", [StudentController::class, "destroy"])->name('student.destroy');
Route::get("/student/{id}", [StudentController::class, "edit"])->name('student.edit');

Route::put("/student/{id}", [StudentController::class, "update"])->name('student.update');


Route::get('/test-success', function () {
    return redirect()->route('student')->with('success', 'Test message works!');
});