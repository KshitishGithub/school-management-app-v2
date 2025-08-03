<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index(Request $request, $id = null)
    {
        if ($id !== null) {
            // Retrieve a specific teacher by id
            $teacher = teacher::find($id);
            // $teacher = teacher::where(['id'=>$id])->first();

            if ($teacher) {
                return response()->json([
                    'status' => true,
                    'data' => $teacher,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher not found',
                ], 404);
            }
        } else {
            // Retrieve all teachers
            $teachers = teacher::orderBy('id', 'desc')
                ->select('id', 'name', 'gender', 'joiningDate', 'qualification', 'experience', 'email', 'address', 'city', 'state', 'zip', 'country', 'image', 'about')
                ->get();

            if ($teachers->isNotEmpty()) {
                return response()->json([
                    'status' => true,
                    'path' => url('uploads/images/teachers/'),
                    'data' => $teachers,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No teachers found',
                ], 404);
            }
        }
    }
}
