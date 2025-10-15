<?php

namespace App\Http\Controllers;

use App\Models\StudentModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{

    public function validator(Request $request, $id){

        return $request->validate([
            'student' => [
                        'required',
                        'string',
                        function ($attribute, $value, $fail) use ($id) {
                $formatted = preg_replace('/\s+/', ' ', ucwords(strtolower($value)));
                $exists = StudentModel::where('student', $formatted)
                    ->where('id', '!=', $id) //  ignore current record if there is no id
                    ->exists();

                if ($exists) {
                    $fail("The {$attribute} name has already been taken.");
                }
            },
        ],
                'grade' => 'required|string',
                'subject' => 'required|string',
                                            //unique:table,column
                'email' => 'required|email',
                Rule::unique('student', 'email')->ignore($id),
            ]);
    }

    public function index(Request $request)
    {
        try {
            // $students = StudentModel::all()
            $students = StudentModel::orderBy('id', 'asc')->get();
                if ($students->isEmpty()) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'message' => 'No student records found.',
                            'data' => []
                        ], 404);
                    }

                    // For Blade view
                    return view("student", [
                        'students' => $students,
                        'message' => 'No student records found.'
                    ]);
                }
                if ($request->wantsJson()){
                    return response()->json([
                        'message'=>'List of Students',
                        'data'=> $students
                    ],200);
                }
                return view("student", compact("students"));

        } catch (Exception $e) {
            if($request->wantsJson()){
                return response()->json([
                    "message"=> "Something went wrong",
                    "error"=> $e->getMessage()
                ],500);
            }
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }   
    }
    public function create()
    {
        return view("student");
    }

    //this handles both web and api
    public function store(Request $request)
    {
        try {

            $validated = $this->validator($request, $id = null );
            
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
            return back()->withInput()->withErrors($e->errors());

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
        return view('student.edit', compact('students'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $this->validator($request, $id);

            $student = StudentModel::findOrFail( $id);
            $student->update($validated);
            

            if( $request->wantsJson()){
                return response()->json([
                    "message" => "Successfully updated",
                    "data" => $student
                ]);
            }
            return redirect()->route("student")->with("success","Student info successfully updated");

        } catch (ValidationException $e) {
            if( $request->wantsJson()){
                return response()->json([
                    "message" => "Validation Error",
                    "errors" => $e->getMessage()
                ]);
            }
            return back()->withErrors( $e->getMessage() );
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
            return redirect()->route('student')->with('success', "Student $student->student deleted successfully.");

        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message'=> "$id no record of this student id.",
                    'error' => $e->getMessage() 
                ]);
            }
            return back()->withErrors(['error' => "No record of student $id."]);
        }
        
    }
}
