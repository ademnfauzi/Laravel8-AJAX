<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index(){
        $students = Student::orderBy('id', 'ASC')->get();
        return view('students', compact('students'));
    }
    public function addStudent(Request $request)
    {
        $student = new Student();
        $student->firstname = $request->firstname;
        $student->lastname = $request->lastname;
        $student->email = $request->email;
        $student->phone = $request->phone;

        $student->save();
        return response()->json($student);
    }
    public function getStudentById($id){
        // $student = new Student();
        $student = Student::find($id);
        return response()->json($student);
    }

    public function updateStudent(Request $request){
        $student = Student::find($request->id);
        $student->firstname = $request->firstname;
        $student->lastname = $request->lastname;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->save();
        return response()->json($student);

    }
}
