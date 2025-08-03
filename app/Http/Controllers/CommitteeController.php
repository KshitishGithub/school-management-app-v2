<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $committees = Committee::orderBy('id', 'desc')->get();
        return view('committee.list_committee', compact('committees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('committee.add_committee');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request........
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:committees,email',
            'mobile' => 'required|numeric|unique:committees,mobile',
            'designation' => 'required',
            'status' => 'required',
            'photo' => 'required|mimes:jpeg,jpg,png|max:1024',
            'signature' => 'required|mimes:jpeg,jpg,png|max:1024',
        ]);

        if ($validator->passes()) {
            // Photo
            $photo = $request->file('photo');
            $ext = $photo->getClientOriginalExtension();
            $photoName = time() . '.' . $ext;
            $photo->move(public_path('uploads/images/committee'), $photoName);
            // Signature
            $signature = $request->file('signature');
            $ext = $signature->getClientOriginalExtension();
            $signatureName = time() . '.' . $ext;
            $signature->move(public_path('uploads/images/committee'), $signatureName);

            $committee = new committee;
            $committee->name = $request->name;
            $committee->email = $request->email;
            $committee->designation = $request->designation;
            $committee->mobile = $request->mobile;
            $committee->status = $request->status;
            $committee->photo = $photoName;
            $committee->signature = $signatureName;
            $committee->save();

            return response()->json([
                'status' => true,
                'message' => 'Committee added successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Committee $committee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Committee $committee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Committee $committee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if ($id !== '') {
            $committee = Committee::find($id);
            if ($committee) {
                File::delete(public_path('uploads/images/committee/' . $committee->photo));
                File::delete(public_path('uploads/images/committee/' . $committee->signature));
                $committee->delete();
                return response()->json(array('status' => true, 'message' => 'Committee deleted successfully.'));
            } else {
                return response()->json(array('status' => false, 'message' => 'Committee not found.'));
            }
        } else {
            return response()->json(array('status' => false, 'message' => 'Committee not found.'));
        }
    }
}
