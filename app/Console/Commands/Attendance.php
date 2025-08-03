<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Attendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Absence automatic of the absence studnets

        // Current session
        $session = DB::table('sessions')->where('active', '1')->value('id');

        if (!$session) {
            session()->flash('success', 'No active session found');
        }

        // Get all active students
        $activeStudents = DB::table('students')
            ->where('status', '1')
            ->where('session_id', $session)
            ->pluck('registration_id'); // Get registration IDs as a collection

        // Get attendees for today
        $attendees = DB::table('attendances')
            ->where('session', $session)
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->pluck('registration_id'); // Get registration IDs as a collection

        // If no attendance found for today
        if ($attendees->isEmpty()) {
            return false;
            // session()->flash('success', 'No attendance records found for today');
        }

        // Find absent students
        $absentStudents = $activeStudents->diff($attendees)->values();

        // absent students
        $absentStudents = DB::table('students')
            ->where('session_id', $session)
            ->where('students.status','=','1')
            ->whereIn('registration_id', $absentStudents)
            ->select('registration_id', 'session_id', 'class_id', 'section_id', 'roll_no')
            ->get();

        // Insert absence data into the attendance table
        $insertData = [];
        foreach ($absentStudents as $student) {
            $insertData[] = [
                'registration_id' => $student->registration_id,
                'session' => $student->session_id,
                'class' => $student->class_id,
                'section' => $student->section_id,
                'roll' => $student->roll_no,
                'attendance' => 'A',
                'attendance_by' => '0',
                'attendance_from' => 'Web',
                'attendance_type' => 'Automatically',
                'inOutStatus' => '0',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert into the attendance table
        DB::table('attendances')->insert($insertData);
        // session()->flash('success', 'Automatic absence captured.');
        return true;
    }
}
