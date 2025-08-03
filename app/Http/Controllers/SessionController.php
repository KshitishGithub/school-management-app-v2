<?php

namespace App\Http\Controllers;

use App\Models\session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    // Section
    public function index()
    {
        $sessions = DB::table('sessions')->get();
        $sessionslist = DB::table('sessions')->get();
        return view('session.session', compact('sessions', 'sessionslist'));
    }


    // Add section
    public function addSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session' => 'required',
        ]);

        if ($validator->passes()) {
            $session = new session();
            $session->session = $request->session;
            $session->active = 0;
            $session->save();
            session()->flash('success', 'Session added successfully.');
            return response()->json([
                'status' => true,
                'message' => "Session added successfully.",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
    }


    // Change Session ........
    public function changeSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'changeSession' => 'required',
        ]);

        if ($validator->passes()) {

            $session = session::where('active', '1')->first();
            if ($session) {
                $session->active = 0;
                $session->save();
            }

            $updateSession = session::find($request->changeSession);
            $updateSession->active = 1;
            $updateSession->save();
            session()->flash('success', 'Session changed successfully.');
            return response()->json([
                'status' => true,
                'message' => "Session changed successfully.",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Please change session.",
            ]);
        }
    }
}
