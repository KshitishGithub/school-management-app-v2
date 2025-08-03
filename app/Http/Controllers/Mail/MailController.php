<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Mail\resetPassword;
use App\Models\setting;
use Illuminate\Http\Request;
use App\Mail\testmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Exception;

class MailController extends Controller
{
    public function test()
    {
        $mailData = [
            'title' => 'Test Mail from Kshitish',
            'body' => 'This is the body . Test Mail from Kshitish',
        ];
        Mail::to('official.kshitish@gmail.com')->queue(new testmail($mailData));

        //    dd('Mail sent successfully');
    }


    // Send password reset link to current user
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $settings = setting::get();

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = Str::random(32);
            $name = $user->name;
            $email = $user->email;

            $user->password_token = $token;
            $user->password_status = '1';
            $user->save();

            $mailData = [
                'name' => $name,
                'email' => $email,
                'token' => $token,
                'url' => url('/'),
                'settings' => $settings[0]
            ];

            try {
                Mail::to($email)->queue(new resetPassword($mailData));
                return redirect()->back()->with('success', 'Reset password link sent successfully.');
            } catch (Exception $e) {
                return redirect()->back()->with('error', 'Failed to send reset password link.');
            }
        } else {
            return redirect()->back()->with('error', 'Invalid email address.');
        }
    }
}
