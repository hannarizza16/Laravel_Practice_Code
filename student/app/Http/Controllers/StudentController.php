<?php

namespace App\Http\Controllers;

use App\Models\StudentModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{

    public function index(Request $request)
    {
        $students = StudentModel::orderBy('id', 'asc')->get();
        if ($request->wantsJson()){
            return response()->json([
                'message'=>'List of Students',
                'data'=> $students
            ]);
        }                               //compact  - is like "students" => "students"
        // this passes to blade 
        return view("student", compact("students"));
    
    }
    public function create()
    {
        return view("student");
    }

    public function store(Request $request)
    {
        try {
            // validate
            $validated = $request->validate([
                'student' => [
                        'required',
                        'string',
                        function ($attribute, $value, $fail) {
                            $formatted = preg_replace('/\s+/', ' ', ucwords(strtolower($value)));
                            if (StudentModel::where('student', $formatted)->exists()) {
                                $fail("The {$attribute} name has already been taken."); // The student name has already been taken
                            }
                        },
                    ],
                'grade' => 'required|string',
                'subject' => 'required|string',
                                            //unique:table,column
                'email' => 'required|email|unique:students,email'
            ]);
            
            $student = StudentModel::create($validated);

            if ($request->wantsJson()){
                return response()->json([
                    'message'=> "student added successfully!",
                    'data' => $student
                ]);
            }
        
            return redirect()->route('student')->with('success', 'Student added!');


            // catches errors for validation 
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

    public function edit($id)
    {
        $students = StudentModel::findOrFail($id);

        return view("student", compact("students"));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                "student" => "required|string|unique:students,student",
                "grade" => "required|string",
                "subject" => "required|string",
                "email" => "required|email|unique:students,email"
            ]);

             //find id
            $student = StudentModel::findOrFail($id);

            //check if record exist 
            if(!$student){
                return response()->json([
                    'message'=> 'Student not found'
                ],404);
            }

            // else update
            $student->update($validated);

            if($request->wantsJson()){
                return response()->json([
                    'message' => "$student->student updated successfuly",
                    "data" => $student
                ]);
            }

            return redirect()->route('student')->with("success", "$student->student updated successfuly"); 
        
        } catch (ValidationException $e) {
            if($request->wantsJson()){
                return response()->json([
                    'message'=> "Validation Failed. we cant update this student",
                    'errors' => $e->errors()
                ]);
            }

            return back()->withErrors(['errors' => 'Something went wrong' . $e->getMessage()]);
        } catch (Exception $e) {
            if($request->wantsJson()) {
                return response()->json([
                    'message' => 'Something went wrong',
                    'error' => $e->getMessage()
                ]); 
            }
            return back()->withErrors(['error'=> $e->getMessage()]);

        }
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
            return redirect()->route('student')->with('success', "Student ID $student->student deleted successfully.");

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
