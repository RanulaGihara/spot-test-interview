<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentCreated;


class StudentController extends Controller
{

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'first_name' => 'required|string',
                'middle_name' => 'nullable|string',
                'last_name' => 'required|string',
                'parents_id' => 'nullable',
                'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'birthdate' => 'required|date',
                'address_one' => 'required|string',
                'city' => 'required|string',
                'district' => 'required|string',
            ]);
            if ($request->hasFile('profile_image')) {
                // todo Upload and store the profile image, and set the 'profile_image' field in $data ( didn't get an enough time to implement)
            }
            $student = Student::create($data);

            // todo Send an email to the parent email ( SMTP configration error is there didn't get an enough time to fix)
//            Mail::to($student->parent->email)->send(new StudentCreated($student));

            return response()->json($student, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create student. ' . $e->getMessage()], 500);
        }
    }

    public function getStudentList()
    {
        $students = Student::select('id', 'first_name', 'middle_name', 'last_name', 'birthdate', 'address_one', 'city', 'district', 'parents_id')
            ->with('parent')
            ->get();

        return response()->json($students);
    }
    public function getStudentDetailsById($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        return response()->json($student);
    }

    public function updateStudentById(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->update($request->all());

        return response()->json(['message' => 'Student updated successfully']);
    }

    public function deleteStudent($id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();

            return response()->json(['message' => 'Student deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete student'], 500);
        }
    }

}
