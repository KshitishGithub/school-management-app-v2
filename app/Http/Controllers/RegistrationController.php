<?php

namespace App\Http\Controllers;

use App\Models\BusStop;
use App\Models\class_manage;
use App\Models\registration;
use App\Models\Route;
use App\Models\section;
use App\Models\session;
use App\Models\Student;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function index()
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Current years session
        $classes = class_manage::get();
        $session = session::where('active', 1)->first();
        $routes = Route::all();
        return view('registration.registration', [
            'classes' => $classes,
            'session' => $session,
            'routes' => $routes,
        ]);
    }


    // Get Section after selecting class
    public function getSection(Request $request)
    {
        if ($request != null) {
            $sections = section::where('class_manages_id', $request->class_id)
                ->select('id', 'section')
                ->get();
            if ($sections->count() == 0) {
                return response()->json([
                    'status' => false,
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'sections' => $sections
                ]);
            }
        }
    }


    // Registration ........
    public function registration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session' => 'required',
            'class' => 'required',
            'name' => 'required',
            'dob' => 'required',
            'fathers_name' => 'required',
            'mothers_name' => 'required',
            'mobile' => 'required|size:10',
            'nationality' => 'required',
            'religion' => 'required',
            'caste' => 'required',
            'gander' => 'required',
            'village' => 'required',
            'post_office' => 'required',
            'police_station' => 'required',
            'district' => 'required',
            'pin' => 'required|size:6',
            // 'aadhar' => 'required',
            'photo' => 'required|mimes:jpeg,jpg,png|max:1024',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        } else {
            // Check if the student already exists
            $existingStudent = Registration::where('name', $request->name)
                ->where('dateOfBirth', $request->dob)
                ->where('fathersName', $request->fathers_name)
                ->first();

            if ($existingStudent) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student already registered.',
                ]);
            }

            $registration = new registration;

            $photo = $request->file('photo');
            $ext = $photo->getClientOriginalExtension();
            $photoName = time() . '.' . $ext;
            $photo->move(public_path('uploads/images/registration'), $photoName);


            $registration->session = $request->input('session');
            $registration->class = $request->class;
            $registration->section = $request->section;
            $registration->name = $request->name;
            $registration->dateOfBirth = $request->dob;
            $registration->fathersName = $request->fathers_name;
            $registration->fathersQualification = $request->fathers_qualification;
            $registration->fathersOccupation = $request->fathers_occupation;
            $registration->mothersName = $request->mothers_name;
            $registration->mothersQualification     = $request->mothers_qualification;
            $registration->mothersOccupation = $request->mothers_occupation;
            $registration->mobile = $request->mobile;
            $registration->whatsapp = $request->whatsapp_no;
            $registration->blood_group = $request->blood_group;
            $registration->nationality = $request->nationality;
            $registration->religion = $request->religion;
            $registration->caste = $request->caste;
            $registration->gander = $request->gander;
            $registration->village = $request->village;
            $registration->postOffice = $request->post_office;
            $registration->policeStation = $request->police_station;
            $registration->district = $request->district;
            $registration->pin = $request->pin;
            $registration->aadhar = $request->aadhar;
            $registration->photo = $photoName;
            $registration->transport = $request->transport;
            $registration->route = $request->route;
            $registration->stops = $request->bus_stops;
            $registration->hostel = $request->hostel;
            $registration->mess = $request->mess;
            $registration->status = '1';
            $registration->role = 'student';
            $registration->save();

            return response()->json([
                'status' => true,
                'message' => 'Registration successfully.',
            ]);
        }
    }

    // Search registered students.......
    public function registered(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Current years session
        $classes = class_manage::get();

        $regStudents = DB::table('registrations as r')
            ->join('class_manages as c', 'r.class', '=', 'c.id')
            ->join('sessions as sn', 'r.session', '=', 'sn.id')
            ->leftJoin('sections as s', 'r.section', '=', 's.id')
            ->select(['r.id', 'r.name', 'r.dateOfBirth', 'r.fathersName', 'r.mobile', 'r.photo', 'r.created_at',  'c.class', 's.section'])
            ->where('sn.active', '1')
            ->where('r.status', '1');

        if ($request->class != null) {
            $regStudents = $regStudents->where('c.id', $request->class);
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
            $regStudents = $regStudents->where('r.mobile', 'LIKE', '%' . $request->mobile . '%');
        }

        $regStudents = $regStudents->get();

        return view('registration.registered', compact('regStudents', 'classes'));
    }



    // Preview
    public function preview(Request $request)
    {
        if (!empty($request->registration_id)) {
            $previewStudent = DB::table('registrations as r')
                ->where('r.id', $request->registration_id)
                ->join('class_manages as c', 'r.class', '=', 'c.id')
                ->join('sessions', 'r.session', '=', 'sessions.id')
                ->leftJoin('sections as s', 'r.section', '=', 's.id')
                ->leftJoin('routes as ro', 'ro.id', '=', 'r.route')
                ->leftJoin('bus_stops as st', 'r.stops', '=', 'st.id')
                ->select(['r.*', 'c.class', 's.section', 'sessions.session', 'st.bus_stops', 'ro.route'])
                ->first();

            if ($previewStudent) {
                return response()->json([
                    'status' => true,
                    'message' => $previewStudent,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Record not found.",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Registration ID not provided.",
            ]);
        }
    }

    // Edit ......
    public function edit(Request $request, $id)
    {
        try {
            $id = decrypt($id);
            // dd($decryptedId);
        } catch (DecryptException $e) {
            return redirect('404');
        }
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->session;
        // Current years session
        $classes = class_manage::get();
        $routes = Route::all();

        if (!empty($id)) {
            $student = DB::table('registrations as r')
                ->where('r.id', $id)
                ->join('class_manages as c', 'r.class', '=', 'c.id')
                ->leftJoin('sections as s', 'r.section', '=', 's.id')
                ->select(['r.*', 'c.class', 'c.id as class_id', 's.section', 's.id as section_id'])
                ->first();
            if ($student) {
                $sections = DB::table('sections')
                    ->where('class_manages_id', $student->class_id)
                    ->get();
                $bus_stops = BusStop::where('route_id', $student->route)->get();
                return view('registration.registration_edit', compact('student', 'session', 'classes', 'sections', 'routes', 'bus_stops'));
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Record not found',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Student id not found.',
            ]);
        }
    }
    // Edit After Admit......
    public function editStudents(Request $request, $id)
    {
        try {
            $id = decrypt($id);
            // dd($decryptedId);
        } catch (DecryptException $e) {
            return redirect('404');
        }
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->session;
        // Current years session
        $classes = class_manage::get();
        $routes = Route::all();

        if (!empty($id)) {
            $student = DB::table('registrations as r')
                ->where('r.id', $id)
                ->join('class_manages as c', 'r.class', '=', 'c.id')
                ->leftJoin('sections as s', 'r.section', '=', 's.id')
                ->select(['r.*', 'c.class', 'c.id as class_id', 's.section', 's.id as section_id'])
                ->first();
            if ($student) {
                $sections = DB::table('sections')
                    ->where('class_manages_id', $student->class_id)
                    ->get();
                $bus_stops = BusStop::where('route_id', $student->route)->get();
                return view('student.admitted_students_edit', compact('student', 'session', 'classes', 'sections', 'routes', 'bus_stops'));
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Record not found',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Student id not found.',
            ]);
        }
    }
    // Registration Update
    public function update(Request $request, $id)
    {
        try {
            $id = decrypt($id);
            // dd($decryptedId);
        } catch (DecryptException $e) {
            return redirect('404');
        }

        if (!empty($id)) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'dob' => 'required',
                'fathers_name' => 'required',
                'mothers_name' => 'required',
                // 'mobile' => 'required|digits:10|unique:registrations,mobile,' . $id,
                'mobile' => 'required|digits:10',
                'nationality' => 'required',
                'religion' => 'required',
                'caste' => 'required',
                'gander' => 'required',
                'village' => 'required',
                'post_office' => 'required',
                'police_station' => 'required',
                'district' => 'required',
                'pin' => 'required|digits:6',
                // 'aadhar' => 'required',
                'photo' => 'nullable|mimes:jpeg,jpg,png|max:1024',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ]);
            } else {
                $registration = Registration::find($id);

                // Check if a new photo is uploaded
                if ($request->hasFile('photo')) {
                    // Remove the old photo if it exists
                    if ($registration->photo) {
                        $oldPhotoPath = public_path('uploads/images/registration/' . $registration->photo);
                        if (File::exists($oldPhotoPath)) {
                            File::delete($oldPhotoPath);
                        }
                    }

                    // Upload the new photo
                    $photo = $request->file('photo');
                    $ext = $photo->getClientOriginalExtension();
                    $photoName = time() . '.' . $ext;
                    $photo->move(public_path('uploads/images/registration'), $photoName);

                    // Update the photo attribute in the database
                    $registration->photo = $photoName;
                }

                // Update other form fields
                $registration->name = $request->name;
                $registration->dateOfBirth = $request->dob;
                $registration->fathersName = $request->fathers_name;
                $registration->fathersQualification = $request->fathers_qualification;
                $registration->fathersOccupation = $request->fathers_occupation;
                $registration->mothersName = $request->mothers_name;
                $registration->mothersQualification = $request->mothers_qualification;
                $registration->mothersOccupation = $request->mothers_occupation;
                $registration->mobile = $request->mobile;
                $registration->whatsapp = $request->whatsapp_no;
                $registration->nationality = $request->nationality;
                $registration->religion = $request->religion;
                $registration->caste = $request->caste;
                $registration->gander = $request->gander;
                $registration->village = $request->village;
                $registration->postOffice = $request->post_office;
                $registration->policeStation = $request->police_station;
                $registration->district = $request->district;
                $registration->pin = $request->pin;
                $registration->aadhar = $request->aadhar;
                $registration->transport = $request->transport ?? 'No';
                $registration->route = $request->route;
                $registration->stops = $request->bus_stops;
                $registration->hostel = $request->hostel ?? 'No';
                $registration->mess = $request->mess ?? 'No';
                $registration->status = '1';
                $registration->role = 'student';
                $registration->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Updated successfully.',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Student id not found.'
            ]);
        }
    }

    // Update after admission period
    public function updateAfterAdmission(Request $request, $id)
    {
        try {
            $id = decrypt($id);
            // dd($decryptedId);
        } catch (DecryptException $e) {
            return redirect('404');
        }
        
        if (!empty($id)) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'dob' => 'required',
                'fathers_name' => 'required',
                'mothers_name' => 'required',
                // 'mobile' => 'required|digits:10|unique:registrations,mobile,' . $id,
                'mobile' => 'required|digits:10',
                'nationality' => 'required',
                'religion' => 'required',
                'caste' => 'required',
                'gander' => 'required',
                'village' => 'required',
                'post_office' => 'required',
                'police_station' => 'required',
                'district' => 'required',
                'pin' => 'required|digits:6',
                // 'aadhar' => 'required',
                'photo' => 'nullable|mimes:jpeg,jpg,png|max:1024',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ]);
            } else {
                $registration = Registration::find($id);

                // Check if a new photo is uploaded
                if ($request->hasFile('photo')) {
                    // Remove the old photo if it exists
                    if ($registration->photo) {
                        $oldPhotoPath = public_path('uploads/images/registration/' . $registration->photo);
                        if (File::exists($oldPhotoPath)) {
                            File::delete($oldPhotoPath);
                        }
                    }

                    // Upload the new photo
                    $photo = $request->file('photo');
                    $ext = $photo->getClientOriginalExtension();
                    $photoName = time() . '.' . $ext;
                    $photo->move(public_path('uploads/images/registration'), $photoName);

                    // Update the photo attribute in the database
                    $registration->photo = $photoName;
                }

                // Update other form fields
                $registration->name = $request->name;
                $registration->dateOfBirth = $request->dob;
                $registration->fathersName = $request->fathers_name;
                $registration->fathersQualification = $request->fathers_qualification;
                $registration->fathersOccupation = $request->fathers_occupation;
                $registration->mothersName = $request->mothers_name;
                $registration->mothersQualification = $request->mothers_qualification;
                $registration->mothersOccupation = $request->mothers_occupation;
                $registration->mobile = $request->mobile;
                $registration->whatsapp = $request->whatsapp_no;
                $registration->nationality = $request->nationality;
                $registration->religion = $request->religion;
                $registration->caste = $request->caste;
                $registration->gander = $request->gander;
                $registration->village = $request->village;
                $registration->postOffice = $request->post_office;
                $registration->policeStation = $request->police_station;
                $registration->district = $request->district;
                $registration->pin = $request->pin;
                $registration->aadhar = $request->aadhar;
                $registration->transport = $request->transport ?? 'No';
                $registration->route = $request->route ?? 'Null';
                $registration->stops = $request->bus_stops ?? 'Null';
                $registration->hostel = $request->hostel ?? 'No';
                $registration->mess = $request->mess ?? 'No';
                $registration->status = '2';
                $registration->role = 'student';
                $registration->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Updated successfully.',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Student id not found.'
            ]);
        }
    }



    // Student Admit ............
    public function admit(Request $request)
    {
        $id = $request->admit_id;

        if (!empty($id)) {
            $registeredStudent = Registration::find($id);

            if ($registeredStudent) {
                // Check if the section exists for the given session and class
                // $sectionExists = Section::where('session_id', '=', $registeredStudent->session)
                //                     ->where('class_id', '=', $registeredStudent->class)
                //                     ->exists();

                $sectionExists = section::where('class_manages_id', $registeredStudent->class)->first();

                if ($sectionExists) {
                    // Check the students section if exits or not
                    $existingStudent = Student::where('session_id', '=', $registeredStudent->session)
                        ->where('class_id', '=', $registeredStudent->class)
                        ->where('section_id', '=', $registeredStudent->section)
                        ->orderBy('roll_no', 'desc')
                        ->first();

                    $admitted = new Student;
                    $admitted->registration_id = $registeredStudent->id;
                    $admitted->session_id = $registeredStudent->session;
                    $admitted->class_id = $registeredStudent->class;
                    $admitted->section_id = $registeredStudent->section;

                    if ($existingStudent) {
                        // Increment roll number if students with same session, class, and section exist
                        $admitted->roll_no = $existingStudent->roll_no + 1;
                    } else {
                        // If no students with the same session, class, and section exist, start from 1
                        $admitted->roll_no = 1;
                    }

                    // Save the admitted student
                    $admitted->save();

                    // Update the registraiton students status ..........
                    $registeredStudent->status = 2;   // 2 means admitted .....
                    $registeredStudent->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'Student admitted successfully.',
                        'data' => $admitted // Return the admitted student data if needed
                    ]);
                } else {
                    // If the section does not exist, increment the roll number for the same class
                    $existingStudent = Student::where('session_id', '=', $registeredStudent->session)
                        ->where('class_id', '=', $registeredStudent->class)
                        ->orderBy('roll_no', 'desc')
                        ->first();

                    $admitted = new Student;
                    $admitted->registration_id = $registeredStudent->id;
                    $admitted->session_id = $registeredStudent->session;
                    $admitted->class_id = $registeredStudent->class;
                    $admitted->section_id = null; // No section distinction

                    if ($existingStudent) {
                        $admitted->roll_no = $existingStudent->roll_no + 1; // Increment roll number for the same class
                    } else {
                        $admitted->roll_no = 1; // Start roll number from 1 for this class
                    }

                    // Save the admitted student
                    $admitted->save();
                    // Update the registraiton students status ..........
                    $registeredStudent->status = 2;   // 2 means admitted .....
                    $registeredStudent->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'Student admitted successfully.',
                        'data' => $admitted // Return the admitted student data if needed
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found.'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Student ID not found.'
            ]);
        }
    }


    // Delete student
    public function deleteStudent(Request $request)
    {
        if ($request->delete_id !== '') {
            $student = Registration::find($request->delete_id);
            if ($student) {
                $oldPhotoPath = public_path('uploads/images/registration/' . $student->photo);
                if(File::exists($oldPhotoPath)){
                    File::delete($oldPhotoPath);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Deleted successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found.'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Student ID not found.'
            ]);
        }
    }
}
