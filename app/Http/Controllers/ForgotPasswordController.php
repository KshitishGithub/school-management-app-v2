<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('auth.forgot-password');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $token = $request->token;

        // Check if the token is empty or doesn't exist in the database
        if (empty($token)) {
            return abort(400, 'Token not provided');
        }

        $user = DB::table('users')->where('password_token','=', $token)
            ->where('password_status','=','1')
            ->where('updated_at', '>=', now()->subMinutes(30))
            ->first();

        // Check if the user with the provided token exists and meets the criteria
        if (!$user) {
            return abort(400, 'Invalid or expired token');
        }

        return view('auth.update-password', compact('token'));
    }


    // updateNewPassword
    public function updateNewPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $token = $request->token;
        $user = User::where('password_token', $token)
            ->where('password_status', '1')
            ->where('updated_at', '>=', Carbon::now()->subMinutes(30))
            ->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->password_status = '0'; // Assuming '0' means the password is updated
            $user->save();

            return redirect()->route('admin.login')->with('success', 'Password updated successfully');
        } else {
            return redirect()->back()->with('error', 'Invalid request.');
        }
    }
}
