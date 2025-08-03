<?php

namespace App\Http\Controllers;

use App\Models\class_manage;
use App\Models\subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function index()
    {

        $subjects = DB::table("subjects")
            ->join("class_manages", "subjects.class_manages_id", "=", "class_manages.id")
            // ->join('sessions as sn', 'subjects.session_id', '=', 'sn.id')
            // ->where('sn.active', '1')
            ->orderBy("class_manages_id")
            ->orderBy("subject")
            ->select('subjects.*', 'class_manages.class') // Select necessary columns
            ->get()
            ->groupBy('class'); // Group the fetched data by the 'class' field


        return view('subjects.subject_list', compact('subjects'));
    }

    // Create Subject
    public function add()
    {
        // Current session
        $session = DB::table('sessions')->where('active','1')->get()[0]->id;
        // Current years session
        $classes = class_manage::get();

        return view('subjects.add_subject', compact('classes'));
    }

    // Store Subject
    public function store(Request $request)
    {
        // Get active session.....
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        $validator = Validator::make($request->all(), [
            'class_id' => 'required',
            'subject' => 'required',
        ]);

        if ($validator->passes()) {
            $subject = new subject();
            $subject->session_id = $session;
            $subject->class_manages_id = $request->class_id;
            $subject->subject = $request->subject;
            $subject->save();

            // session()->flash('success','Subject added successfully.');

            // Session::flash('toastr', ['type' => 'success', 'message' => 'Class added successfully.']);

            return response()->json([
                'status' => true,
                'message' => "Subject added successfully.",
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
    }

    // Destroy.....
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            $destroySubject = subject::find($request->id);

            if ($destroySubject->delete()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Subject deleted successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $destroySubject->errors(),
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Request not supported.",
            ]);
        }
    }
}
