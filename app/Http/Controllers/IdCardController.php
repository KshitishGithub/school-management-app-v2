<?php

namespace App\Http\Controllers;

use App\Models\class_manage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IdCardController extends Controller
{
    public function index(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Current years session
        $classes = class_manage::get();

        $regStudents = DB::table('registrations as r')
            ->join('students as st', 'r.id', '=', 'st.registration_id')
            ->join('class_manages as c', 'st.class_id', '=', 'c.id')
            ->join('sessions as ss', 'st.session_id', '=', 'ss.id')
            ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
            ->where('st.session_id', '=', $session)
            ->where('r.status', '=', '2')
            ->where('st.status', '=', '1')
            ->select(['r.id', 'r.name', 'r.fathersName', 'r.mobile', 'r.dateOfBirth', 'r.photo', 'c.class', 's.section', 'ss.session', 'st.roll_no']);


        if ($request->class != null) {
            $regStudents = $regStudents->where('st.class_id', $request->class);
        }else {
            // if class is not specified
            $regStudents = $regStudents->where('st.class_id', $classes[0]->id);
        }

        if ($request->name != null) {
            $regStudents = $regStudents->where(function ($query) use ($request) {
                $query->where('r.name', 'LIKE', '%' . $request->name . '%');
            });
        }
        if ($request->father_name != null) {
            $regStudents = $regStudents->where(function ($query) use ($request) {
                $query->where('r.fathersName', 'LIKE', '%' . $request->father_name . '%');
            });
        }
        if ($request->mobile != null) {
            $regStudents = $regStudents->where('r.mobile','LIKE', '%' . $request->mobile . '%');
        }
        $regStudents = $regStudents->paginate(10);
        return view('id_card.idcard', compact('regStudents', 'classes'));
    }
}
