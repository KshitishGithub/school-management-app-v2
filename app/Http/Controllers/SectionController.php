<?php

namespace App\Http\Controllers;

use App\Models\class_manage;
use App\Models\section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    public function index(){
        // Current session
        $session = DB::table('sessions')->where('active','1')->get()[0]->id;

        $sections = DB::table("sections")
                    ->join("class_manages","sections.class_manages_id","=","class_manages.id")
                    ->where('sections.session_id',$session)
                    ->orderBy("class_manages_id")
                    ->orderBy("section")
                    ->get()
                    ->groupBy('class');
        return view('sectoin.list_section',compact('sections'));
    }
    public function add(){
        // Current years session
        $classes = class_manage::get();
        return view('sectoin.add_section',compact('classes'));
    }

    // store section
    public function store(Request $request){
        // Get active session.....
        $session = DB::table('sessions')->where('active','1')->get();

        $validator = Validator::make($request->all(), [
            'class_id'=> 'required',
            'section'=> 'required',
        ]);

        if ($validator->passes()) {
            $section = new section();
            $section->session_id = $session[0]->id;
            $section->class_manages_id = $request->class_id;
            $section->section = $request->section;
            $section->save();

            // session()->flash('success','Section added successfully.');

            // Session::flash('toastr', ['type' => 'success', 'message' => 'Class added successfully.']);

            return response()->json([
                'status' => true,
                'message'=> "Section added successfully.",
            ]);

        }else{

            return response()->json([
                'status' => false,
                'message'=> $validator->errors(),
            ]);

        }
    }
}
