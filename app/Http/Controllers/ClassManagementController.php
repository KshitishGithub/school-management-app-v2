<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\class_manage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ClassManagementController extends Controller
{
    public function index(){
        // $session = DB::table('sessions')->where('active','1')->get()[0]->id;
        // get all classes according to the active session
        $classes = DB::table('class_manages as c')
            ->get();
        return view('class.list_class',compact('classes'));
    }

    public function add(){
        return view('class.add_class');
    }

    public function store(Request $request){
        // Get active session.....
        $session = DB::table('sessions')->where('active','1')->get();
        $validator = Validator::make($request->all(), [
            'class'=> 'required',
        ]);

        if ($validator->passes()) {
            $class = new class_manage();
            // $class->session_id = $session[0]->id;
            $class->class = $request->class;
            $class->save();

            // session()->flash('success','Class added successfully.');

            // Session::flash('toastr', ['type' => 'success', 'message' => 'Class added successfully.']);

            return response()->json([
                'status' => true,
                'message'=> "Class added successfully.",
            ]);

        }else{

            return response()->json([
                'status' => false,
                'message'=> $validator->errors(),
            ]);

        }
    }

    // Delete class
    // public function destroy(Request $request){
    //     if (!empty($request->id)){
    //         $destroyClass = class_manage::find( $request->id );
    //         if($destroyClass->delete() ){
    //             // session()->flash('success','Class deleted successfully.');
    //             return response()->json([
    //                 'status'=> true,
    //                 'message'=> 'Class deleted successfully.',
    //             ]);
    //         }else{
    //             return response()->json([
    //                 'status'=> false,
    //                 'message'=> $destroyClass->errors(),
    //             ]);
    //         }
    //     }else{
    //         return response()->json([
    //             'status'=> false,
    //             'message'=> "Request not supported.",
    //         ]);
    //     }
    // }
}
