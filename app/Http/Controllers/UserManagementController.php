<?php

namespace App\Http\Controllers;

use App\Models\user;
use App\Models\user_management;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = user::get();
        return view('usermanagement.list_users', compact('users'));
    }

    public function add()
    {
        return view('usermanagement.add_user');
    }

    // public function store(Request $request)
    // {

    // }

    public function edit($id)
    {
        if (!empty($id)) {
            $user = user::find($id);
            if ($user) {
                return view('usermanagement.user_update', compact('user'));
            }
        }
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $request->user_id,
            'username' => 'required|unique:users,username,' . $request->user_id,
            'phone_number' => 'required|numeric|unique:users,phone,' . $request->user_id,
            'status' => 'required',
            'role_name' => 'required',
            'profile_photo' => 'mimes:jpeg,jpg,png|max:1024',
        ]);
        // Return the requeste for found errors
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        $user = user::find($request->user_id);
        if ($user) {
            // Check if a new photo is uploaded
            if ($request->hasFile('profile_photo')) {
                // Remove the old photo if it exists
                if ($user->profile_image) {
                    $path = public_path('uploads/images/user/' . $user->profile_image);
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }

                // Upload the new photo
                $photo = $request->file('profile_photo');
                $ext = $photo->getClientOriginalExtension();
                $photoName = time() . '.' . $ext;
                $photo->move(public_path('uploads/images/user'), $photoName);

                // Update the photo attribute in the database
                $user->profile_image = $photoName;
            }

            // Update the user
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->phone = $request->phone_number;
            $user->status = $request->status;
            $user->role = $request->role_name;
            $user->save();

            session()->flash('success', 'User updated successfully.');
            return response()->json([
                'status' => true,
                'message' => 'User updated successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "User not found.",
            ]);
        }
    }

    public function destroy(Request $request)
    {
        if ($request->id !== '') {
            $user = user::find($request->id);
            if ($user) {
                $path = public_path('uploads/images/user/' . $user->profile_image);
                if (File::exists($path)) {
                    File::delete($path);
                }
                $user->delete();
                session()->flash('success', 'User deleted successfully.');
                return response()->json(array('status' => true, 'message' => 'User deleted successfully.'));
            } else {
                return response()->json(array('status' => false, 'message' => 'User not found.'));
            }
        } else {
            return response()->json(array('status' => false, 'message' => 'User not found.'));
        }
    }
}
