<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Mail\AddUser;
use App\Models\setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AddUserController extends Controller
{
    // Add User Mail
    public function store(Request $request)
    {
        // Validate the request........
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'username' => 'required|unique:users,username',
            'phone_number' => 'required|numeric|unique:users,phone',
            'status' => 'required',
            'role_name' => 'required',
            'password' => 'required|min:8',
            'profile_photo' => 'required|mimes:jpeg,jpg,png|max:1024',
        ]);

        if ($validator->passes()) {
            $photo = $request->file('profile_photo');
            $ext = $photo->getClientOriginalExtension();
            $photoName = time() . '.' . $ext;
            $photo->move(public_path('uploads/images/user'), $photoName);

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->phone = $request->phone_number;
            $user->status = $request->status;
            $user->role = $request->role_name;
            $user->profile_image = $photoName;
            $user->password = Hash::make($request->password);
            $user->save();

            $settings = setting::get();

            if ($request->role_name == '3') {
                $role = 'Admin';
            } else if ($request->role_name == '2') {
                $role = 'User';
            } else {
                $role = 'Teacher';
            }

            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => $role,
                'settings' => $settings[0]
            ];

            try {
                Mail::to($request->email)->queue(new AddUser($mailData));

                session()->flash('success', 'User added successfully.');
                return response()->json([
                    'status' => true,
                ]);
            } catch (Exception $e) {
                session()->flash('error', 'Failed to add user.' . $e->getMessage());
                return response()->json([
                    'status' => false,
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
    }
}
