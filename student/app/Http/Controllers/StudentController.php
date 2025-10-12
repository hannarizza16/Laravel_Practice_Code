<?php

namespace App\Http\Controllers;

use App\Models\StudentModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{

    public function index(Request $request)
    {
        $students = StudentModel::all();
        if ($request->wantsJson()){
            return response()->json([
                'message'=>'List of Students',
                'data'=> $students
            ]);
        }
        return view("student", compact("students"));
    
    }
    public function create()
    {
        return view("student");
    }

    //this handles both web and api
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                                                //unique:table,column
                'student' => 'required|string|unique:students,student',
                'grade' => 'required|string',
                'subject' => 'required|string',
            ]);

            $student = StudentModel::create($validated);

            if ($request->wantsJson()){
                return response()->json([
                    'message'=> "student added successfully!",
                    'data' => $student
                ]);
            }
        
            return redirect()->route('student')->with('success', 'Student added!');

        } catch(ValidationException $e) {
            if ($request->wantsJson()){
                return response()->json([
                    'message' => 'Validation Failed',
                    'errors' => $e->errors()
                ]);
            }
            return back()->withErrors($e->errors());

        } catch (Exception $e) {
            if ($request->wantsJson()){
                return response()->json([
                    'message'=> "Something went wrong",
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }



    public function show(Request $request, $id)
    {
        try {
            $student = StudentModel::findOrFail($id);
            
            if($request->wantsJson()) {
                return response()->json([
                    'message'=> 'student retrieved successfully',
                    'data'=> $student
                ]);
            };
            
            return view('student', compact('student'));
            
        } catch (Exception $e) {
            return response()->json([
                "message" => "$id : Not registered",
                "error" => $e->getMessage()
            ]);
        }
    }

    public function edit(StudentModel $studentModel)
    {
        //
    }

    public function update(Request $request, StudentModel $studentModel)
    {
        //
    }

    public function destroy(Request $request, $id)
    {
        try {
            $student = StudentModel::findOrFail($id);
            $student->delete();

            if($request->wantsJson()){
                return response()->json([
                    'message'=> "$id deleted",
                    'data' => $student
                ]);
            }
            return redirect()->route('student')->with('success', "Student ID $id deleted successfully.");

        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message'=> "$id no record of this student id.",
                    'error' => $e->getMessage() 
                ]);
            }
            return back()->withErrors(['error' => "No record of student ID $id."]);
        }
        
    }
}
