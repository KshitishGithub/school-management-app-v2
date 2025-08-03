<?php

namespace App\Http\Controllers;

use App\Models\class_manage;
use App\Models\FingerPrint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FingerPrintController extends Controller
{
    // Fingerprint
    public function index(Request $request)
    {

        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Current years session
        $classes = class_manage::get();
        $class = $request->input('class');

        $students = DB::table('registrations as r')
            ->join('students as st', 'r.id', '=', 'st.registration_id')
            ->join('class_manages as c', 'st.class_id', '=', 'c.id')
            ->join('sessions as ss', 'st.session_id', '=', 'ss.id')
            ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
            ->where('st.session_id', '=', $session)
            ->where('r.status', '=', '2')
            ->where('st.class_id', '=', $class)
            ->select(['r.id', 'r.name', 'r.fathersName', 'r.mobile', 'r.dateOfBirth', 'r.photo', 'c.class', 's.section', 'ss.session', 'st.roll_no', 'st.status']);

        $students = $students->paginate(10);
        return view('finger_print.finger_print', compact('students', 'classes'));
    }


    // add fingerprint
    public function add_finger($id)
    {

        if ($id !== '') {
            $students = DB::table('registrations as r')
                ->join('class_manages as c', 'r.class', '=', 'c.id')
                ->join('students as st', 'r.id', '=', 'st.registration_id')
                ->leftJoin('sections as s', 'r.section', '=', 's.id')
                ->select(['r.*', 'c.class', 's.section', 'st.session_id', 'st.roll_no'])
                ->where('r.id', $id)
                ->first();
            if ($students) {

                $fingers = FingerPrint::where('student_id', $id)->get(['finger']);

                $fingerArray = [];

                foreach ($fingers as $fingerData) {
                    $fingerArray[] = $fingerData['finger'];
                }
                return view('finger_print.add_finger_print', compact('students', 'fingerArray'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    // Store fingerprint data
    public function store(Request $request)
    {
        $id = $request->id;
        $finger = $request->finger;
        $isoTemplate = $request->isotemplate;

        $existingFinger = DB::table('finger_prints')->where('student_id', $id)->where('finger', $finger)->first();

        if ($existingFinger) {
            // Convert the result to an array
            $existingFingerArray = (array)$existingFinger;

            // Update the existing record
            DB::table('finger_prints')->where('student_id', $id)->where('finger', $finger)->update([
                'finger' => $finger,
                'isotemplate' => $isoTemplate,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Record updated successfully.',
            ]);
        } else {
            // Create a new record
            DB::table('finger_prints')->insert([
                'student_id' => $id,
                'finger' => $finger,
                'isotemplate' => $isoTemplate,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Record saved successfully.',
            ]);
        }
    }

    // Show fingerprint information
    public function show(Request $request)
    {
        $id = $request->id;
        $finger = $request->finger;

        $isoTemplate = DB::table('finger_prints')->where('student_id', $id)->where('finger', $finger)->first('isotemplate');
        // get the student current information for present
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        $student = DB::table('students as s')->where('registration_id', $id)->where('session_id', $session)->first(['registration_id', 'session_id', 'class_id', 'section_id', 'roll_no']);
        if ($isoTemplate) {
            return response()->json([
                'isoTemplate' => $isoTemplate,
                'student' => $student,
            ]);
        } else {
            return response()->json([
                'isoTemplate' => null,
            ]);
        }
    }
}
