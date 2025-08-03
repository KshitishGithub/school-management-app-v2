<?php

namespace App\Http\Controllers;

use App\Models\class_manage;
use App\Models\Student;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    // Index function and search functions
    public function index(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Current years session
        $classes = class_manage::get();

        $students = DB::table('registrations as r')
            ->join('students as st', 'r.id', '=', 'st.registration_id')
            ->join('class_manages as c', 'st.class_id', '=', 'c.id')
            ->join('sessions as ss', 'st.session_id', '=', 'ss.id')
            ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
            ->where('st.session_id', '=', $session)
            ->where('r.status', '=', '2')
            ->select(['r.id', 'r.name', 'r.fathersName', 'r.mobile', 'r.dateOfBirth', 'r.photo', 'c.class', 's.section', 'ss.session', 'st.roll_no', 'st.status']);


        if ($request->class != null) {
            $students = $students->where('st.class_id', $request->class);
        } else {
            // if class is not specified
            $students = $students->where('st.class_id', $classes[0]->id);
        }

        if ($request->name != null) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('r.name', 'LIKE', '%' . $request->name . '%');
            });
        }
        if ($request->father_name != null) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('r.fathersName', 'LIKE', '%' . $request->father_name . '%');
            });
        }
        if ($request->mobile != null) {
            $students = $students->where('r.mobile', 'LIKE', '%' . $request->mobile . '%');
        }
        $students = $students->paginate(10);
        return view('student.students', compact('students', 'classes'));
    }


    // Student status update
    public function studentStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $studentId = $request->input('student_id');
        $status = $request->input('status');

        // Find the student by ID
        $student = Student::where('registration_id', $studentId)
            ->where('session_id', $session)
            ->first();

        if ($student) {
            // Update the status
            $student->status = $status;
            $student->save();

            // Return a JSON response for successful update
            return response()->json([
                'status' => true,
                'message' => 'Student status updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Student not found',
            ]);
        }
    }

    // Profile Details::
    public function profile($id)
    {
        try {
            $id = decrypt($id);
            // dd($decryptedId);
        } catch (DecryptException $e) {
            return redirect('404');
        }

        if ($id !== '') {
            $students = DB::table('registrations as r')
                ->join('class_manages as c', 'r.class', '=', 'c.id')
                ->join('students as st', 'r.id', '=', 'st.registration_id')
                ->leftJoin('sections as s', 'r.section', '=', 's.id')
                ->select(['r.*', 'c.class', 's.section', 'st.session_id', 'st.roll_no'])
                ->where('r.id', $id)
                ->first();
            if ($students) {
                return view('student.student-profile', compact('students'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    // Pass out the student data
    public function passOut(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        // Next session
        $nextSession = DB::table('sessions')
            ->where('id', '>', $session)
            ->orderBy('id', 'asc')
            ->first();

        if (!$nextSession) {
            return response()->json([
                'status' => false,
                'message' => 'Please add next session , then try to pass out the students'
            ]);
        }

        $nextSessionId = $nextSession ? $nextSession->id : null;

        // Current year's sessions
        $classes = class_manage::get();

        $class = $request->input('class');

        // Next session students
        $nextSessionStudents = DB::table('students as st')
            ->join('registrations as r', 'st.registration_id', '=', 'r.id')
            ->where('st.session_id', '=', $nextSessionId)
            ->where('st.status', '=', '1')
            ->pluck('r.id') // Use pluck to get an array of IDs
            ->toArray();

        // Current session students
        $currentSessionStudents = DB::table('students as st')
            ->join('registrations as r', 'st.registration_id', '=', 'r.id')
            ->where('st.session_id', '=', $session)
            ->where('st.status', '=', '1')
            ->where('st.class_id', '=', $class)
            ->pluck('r.id') // Use pluck to get an array of IDs
            ->toArray();

        // Show pass-out students for pass-out
        $passOutStudents = array_diff($currentSessionStudents, $nextSessionStudents);

        // Fetch current students, including those without marks or exams
        $currentStudents = DB::table('students as st')
            ->join('registrations as r', 'st.registration_id', '=', 'r.id')
            ->join('exams as ex', 'ex.class', '=', 'st.class_id')
            ->join('exams_subjects as es', 'es.exam_id', '=', 'ex.id')
            ->leftJoin('add_marks as am', function ($join) {
                $join->on('am.registration_id', '=', 'st.registration_id')
                    ->on('am.subject_id', '=', 'es.id')
                    ->on('am.roll_no', '=', 'st.roll_no');
            })
            ->whereIn('st.registration_id', $passOutStudents)
            ->where('ex.status', '=', '1')
            ->where('es.subjectType', '=', '1')
            ->select(
                'r.name',
                'r.photo',
                'st.roll_no',
                'st.registration_id as id',
                DB::raw('SUM(am.oral_marks_obtained + am.marks_obtained) as total_marks')
            )
            ->groupBy('r.name', 'r.photo', 'st.roll_no', 'st.registration_id')
            ->orderByDesc('total_marks')
            ->paginate(10);

        // Return the view
        return view('student.students-passout', [
            'classes' => $classes,
            'students' => $currentStudents,
        ]);
    }


    // getClass
    public function getClass(Request $request)
    {
        $classes = class_manage::get();
        return response()->json($classes);
    }

    // pass out the students
    public function passOutByRoll(Request $request)
    {
        $student_id = $request->input('student_id');
        $class = $request->input('class');

        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        // next session
        $nextSession = DB::table('sessions')
            ->where('id', '>', $session)
            ->orderBy('id', 'asc')
            ->first();
        if (!$nextSession) {
            return response()->json([
                'status' => false,
                'message' => 'Please add next session , then try to pass out the students'
            ]);
        }
        $nextSessionId = $nextSession ? $nextSession->id : null;

        $students = DB::table('registrations as r')
            ->where('r.id', '=', $student_id)
            ->get();

        if ($students) {
            $admitStudent = new Student;
            $admitStudent->registration_id = $student_id;
            $admitStudent->session_id = $nextSessionId;
            $admitStudent->class_id = $class;
            $admitStudent->section_id = null;

            // If the section does not exist, increment the roll number for the same class
            $existingStudent = Student::where('session_id', '=', $nextSessionId)
                ->where('class_id', '=', $class)
                ->orderBy('roll_no', 'desc')
                ->first();

            if ($existingStudent) {
                $admitStudent->roll_no = $existingStudent->roll_no + 1;
            } else {
                $admitStudent->roll_no = 1;
            }

            $admitStudent->save();

            return response()->json([
                'status' => true,
                'message' => 'Student admitted successfully.',
                'data' => $admitStudent
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Student not found.'
            ]);
        }
    }
}
