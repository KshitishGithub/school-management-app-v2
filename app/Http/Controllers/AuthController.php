<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    // login
    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        $remember = $request->has('remember');

        $credentials = $request->only('email', 'password');
        $credentials['status'] = 'Active';

        if (Auth::attempt($credentials,$remember)) {
            return redirect()->route('admin.dashboard');
        } else {
            return back()->onlyInput('email')->with('error', 'Invalid credentials or user blocked.');
        }
    }


    // Log out the user
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        // return redirect()->route('admin.login')->with('success', 'Logout successfully');
        return response()->json([
            'status' => true,
        ]);
    }

    // Change the password
    public function changePassword()
    {
        return view('auth.change-password');
    }

    // Store the new password
    public function change_password(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|different:old_password|confirmed',
            'password_confirmation' => 'required',
        ]);

        $user = User::find(Auth::user()->id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $oldPassword = $request->old_password;

        // Check if the old password matches the user's current hashed password
        if (Hash::check($oldPassword, $user->password)) {
            // Check if the new password is different from the old one
            if ($oldPassword !== $request->password) {
                $user->password = Hash::make($request->password);
                $user->save();

                return redirect()->back()->with('success', 'Password updated successfully!');
            } else {
                return redirect()->back()->with('error', 'New password must be different from the old password.');
            }
        } else {
            return redirect()->back()->with('error', 'The old password is incorrect.');
        }
    }
}
