<?php

namespace App\Http\Controllers;

use App\Models\class_manage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HostelController extends Controller
{
    // Hostller Students.....
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
            ->where('st.status', '=', '1')
            ->where('r.hostel', '=', 'YES')
            ->select(['r.id', 'r.name', 'r.fathersName', 'r.mobile', 'r.dateOfBirth', 'r.photo', 'c.class', 's.section', 'ss.session', 'st.roll_no', 'st.status']);


        if ($request->class != null) {
            $students = $students->where('st.class_id', $request->class);
        }else {
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
        return view('hostel.hosteller', compact('students', 'classes'));
    }

    // Present Hosteller
    public function present_hosteller(Request $request){
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Current years session
        $classes = class_manage::get();

        $students = DB::table('registrations as r')
            ->join('students as st', 'r.id', '=', 'st.registration_id')
            ->join('class_manages as c', 'st.class_id', '=', 'c.id')
            ->join('sessions as ss', 'st.session_id', '=', 'ss.id')
            ->join('present_hostellers as ph', 'ph.registration_id', '=', 'r.id')
            ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
            ->where('st.session_id', '=', $session)
            ->where('r.status', '=', '2')
            ->where('st.status', '=', '1')
            ->where('ph.inOutStatus', '=', '1')
            ->where('r.hostel', '=', 'YES')
            ->select(['r.id', 'r.name', 'r.fathersName', 'r.mobile', 'r.dateOfBirth', 'r.photo', 'c.class', 's.section', 'ss.session', 'st.roll_no', 'st.status']);


        if ($request->class != null) {
            $students = $students->where('st.class_id', $request->class);
        }else {
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
        return view('hostel.present_hosteller', compact('students', 'classes'));
    }
}
