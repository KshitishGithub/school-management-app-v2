<?php

namespace App\Http\Controllers;

use App\Models\student_leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
    // Pending leaves --------------------------------
    public function pendingLeave()
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $pendingLeaves = DB::table('student_leaves as sl')
            ->join('class_manages as cm', 'sl.class', '=', 'cm.id')
            ->leftJoin('sections as s', 'sl.section', '=', 's.id')
            ->where('sl.isApproved', 'P')
            ->where('sl.session', $session)
            ->select(
                'sl.id',
                'sl.name',
                'sl.reasons',
                'sl.roll',
                'sl.to_date',
                'sl.from_date',
                'cm.class',
                's.section',
                'sl.letterName',
            )
            ->orderBy('sl.id', 'desc')
            ->get();

        return view('leave.pending_leave', compact('pendingLeaves'));
    }
    // Approved leaves --------------------------------
    public function approvedLeave()
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $approveLeaves = DB::table('student_leaves as sl')
            ->join('class_manages as cm', 'sl.class', '=', 'cm.id')
            ->leftJoin('sections as s', 'sl.section', '=', 's.id')
            ->where('sl.isApproved', 'A')
            ->where('sl.session', $session)
            ->select(
                'sl.id',
                'sl.session',
                'sl.name',
                'sl.reasons',
                'sl.roll',
                'sl.to_date',
                'sl.from_date',
                'sl.from_date',
                'cm.class',
                's.section',
                'sl.approvedBy',
            )
            ->orderBy('sl.id', 'desc')
            ->get();

        return view('leave.approve_leave', compact('approveLeaves'));
    }
    // Reject leaves --------------------------------
    public function reject()
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $rejectLeaves = DB::table('student_leaves as sl')
            ->join('class_manages as cm', 'sl.class', '=', 'cm.id')
            ->leftJoin('sections as s', 'sl.section', '=', 's.id')
            ->where('sl.isApproved', 'R')
            ->where('sl.session', $session)
            ->select(
                'sl.id',
                'sl.session',
                'sl.name',
                'sl.reasons',
                'sl.roll',
                'sl.to_date',
                'sl.from_date',
                'sl.from_date',
                'cm.class',
                's.section',
                'sl.approvedBy',
            )
            ->orderBy('sl.id', 'desc')
            ->get();

        return view('leave.rejected_leave', compact('rejectLeaves'));
    }

    // Leave Delete --------------------------------
    public function destroy(Request $request)
    {
        if (!empty($request->id)) {
            $leave = student_leave::find($request->id);

            Storage::delete('public/images/leave/' . $leave->letterName);

            if ($leave->delete()) {

                // ! Send notificatin function --------------------------------
                $notification = FirebasePushNotification(
                    [$request->s_id],"Dear $request->name",
                    "Your $request->fees_type of $request->amount for the month of $request->month have been received successfully.",
                    "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcShVwg_8v81VD3V4qn89R49mbWM9vzyDodV6A&s");

                    
                return response()->json([
                    'status' => true,
                    'message' => 'Leave deleted successfully.',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $leave->errors(),
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Request not supported.",
            ]);
        }
    }

    // Approved Leave ---------------
    public function approve(Request $request)
    {
        if (!empty($request->id)) {
            $leave = student_leave::find($request->id);
            $leave->isApproved = "A";
            $leave->approvedBy = Auth::user()->name;
            $leave->save();

            return response()->json([
                'status' => true,
                'message' => 'Leave approved successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Request not supported.",
            ]);
        }
    }

    // Rejected the leave
    public function rejectLeave(Request $request)
    {
        if (!empty($request->id)) {
            $leave = student_leave::find($request->id);
            $leave->isApproved = "R";
            $leave->approvedBy = Auth::user()->name;
            $leave->save();

            return response()->json([
                'status' => true,
                'message' => 'Leave rejected successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Request not supported.",
            ]);
        }
    }
}
