<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Models\teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = teacher::orderBy('id','desc')->get();
        return view('teacher.list-teachers',compact('teachers'));
    }

    // Add Teachers
    public function create()
    {
        return view('teacher.add-teacher');
    }

    // Store Teachers.......
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => ['required',],
            'gender' => ['required',],
            'date_of_birth' => ['required',],
            'mobile' => ['required', 'numeric'],
            'joining_date' => ['required',],
            'qualification' => ['required',],
            'experience' => ['required',],
            'email' => ['required', 'email', 'unique:teachers'],
            'address' => ['required',],
            'city' => ['required',],
            'state' => ['required',],
            'zip_code' => ['required', 'size:6'],
            'country' => ['required',],
            'photo' => 'required|mimes:jpeg,jpg,png|max:2048',
            'about' => ['required',],
        ]);

        // Upload photo
        if($request->hasFile('photo')){

        $photo = $request->file('photo');
        $ext = $photo->getClientOriginalExtension();
        $photoName = time() . '.' . $ext;
        $photo->move(public_path('uploads/images/teachers'), $photoName);

        }

        $teacher = new teacher;
        $teacher->name = $request->full_name;
        $teacher->gender = $request->gender;
        $teacher->dob = $request->date_of_birth;
        $teacher->mobile = $request->mobile;
        $teacher->joiningDate = $request->joining_date;
        $teacher->qualification = $request->qualification;
        $teacher->experience = $request->experience;
        $teacher->email = $request->email;
        $teacher->address = $request->address;
        $teacher->city = $request->city;
        $teacher->state = $request->state;
        $teacher->zip = $request->zip_code;
        $teacher->country = $request->country;
        $teacher->image = $photoName;
        $teacher->about = $request->about;
        $teacher->save();

        session()->flash('success','Teacher added successfully.');
        return redirect()->route('teacher.list');
    }

    // Delete Teachers.....
    public function destroy(Request $request){
        if (!empty($request->id)){
            $destroyTeacher = teacher::find( $request->id );
            $path = public_path("uploads/images/teachers/$destroyTeacher->image");

            if ( File::exists( $path ) ) {
                File::delete( $path );
            }

            if($destroyTeacher->delete() ){
                return response()->json([
                    'status'=> true,
                    'message'=> 'Teacher deleted successfully.',
                ]);
            }else{
                return response()->json([
                    'status'=> false,
                    'message'=> $destroyTeacher->errors(),
                ]);
            }
        }else{
            return response()->json([
                'status'=> false,
                'message'=> "Request not supported.",
            ]);
        }
    }

    public function profile($id)
    {
        if ($id !== '') {
            $teachers = DB::table('teachers as t')
                ->where('t.id', $id)
                ->first();
                if  ($teachers) {
                    return view('teacher.teacher_profile', compact('teachers'));
                }else{
                    abort(404);
                }
        } else {
            abort(404);
        }
    }

}
