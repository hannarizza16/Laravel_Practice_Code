<?php

namespace App\Http\Controllers;

use App\Models\StudentModel;
use Exception;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'student' => 'required|string',
                'grade' => 'required|string',
                'subject' => 'required|string',
            ]);

            $student = StudentModel::create($validated);

            // return redirect()->route('student')->with('success', 'Student added!');
            return response()->json([
                'message'=> "student added successfully!",
                'data' => $student
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message'=> "Something went wrong",
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentModel $studentModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StudentModel $studentModel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentModel $studentModel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentModel $studentModel)
    {
        
    }
}
