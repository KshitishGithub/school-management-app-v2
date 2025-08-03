<?php

namespace App\Http\Controllers;

use App\Models\attendance;
use App\Models\class_manage;
use App\Models\evening_attendance;
use App\Models\fees;
use App\Models\presentHosteller;
use App\Models\registration;
use App\Models\setting;
use App\Models\Student;
use App\Models\teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->value('id');
        $settings  = setting::all();

        if ($settings->isNotEmpty()) {
            session()->put('admin_settings', $settings);
        }
        $data = [];
        $data['total_students'] = Student::where('session_id', '=', $session)
                                    ->where('status', '=', '1')
                                    ->count('id');
        $data['total_teachers'] = teacher::count('id');
        $data['total_class'] = class_manage::count('id');
        $data['total_revenue'] = fees::where('session_id', '=', $session)->whereDate('created_at',  Carbon::now()->format('Y-m-d'))->sum('amount');
        $data['present_student'] = attendance::where('session', '=', $session)->where('attendance', '=', 'P')->whereDate('updated_at',  Carbon::now()->format('Y-m-d'))->count('attendance');

        $data['present_hosteller'] = presentHosteller::where('session', '=', $session)->where('inOutStatus', '=', '1')->count();

        $data['out_student'] = attendance::where('session', '=', $session)->where('attendance', '=', 'P')->where('inOutStatus', '=', '0')->whereDate('updated_at',  Carbon::now()->format('Y-m-d'))->count();

        $data['total_hosteller'] = DB::table('registrations as r')
                                    ->where('s.session_id', '=', $session)
                                    ->where('r.hostel', '=', 'Yes')
                                    ->leftJoin('students as s','r.id','s.registration_id')
                                    ->where('s.status', '=', '1')
                                    ->count();

        $data['evening_hostel_student'] = evening_attendance::where('session', '=', $session)->where('attendance', '=', 'P')->whereDate('updated_at',  Carbon::now()->format('Y-m-d'))->count('attendance');
        $data['registered_student'] = registration::where('session', '=', $session)->where('status', '=', '1')->count('id');

        $lastMonth = Carbon::now()->subMonth()->month;
        $data['last_month'] = fees::where('session_id', '=', $session)->whereMonth('created_at',  $lastMonth)->sum('amount');

        $today = Carbon::now()->format('Y-m-d');

        $lastSevenDays = [];
        for ($i = 6; $i >= 0; $i--) {
            $lastSevenDays[] = Carbon::now()->subDays($i)->toDateString();
        }
        $lastSevenDays = $lastSevenDays[0];

        $data['last_seven_days'] = fees::where('session_id', '=', $session)->whereBetween(DB::raw('DATE(created_at)'), [$lastSevenDays, $today])->sum('amount');


        $girlsData = DB::table('students as s')
            ->join('registrations as r', 's.registration_id', 'r.id')
            ->join('class_manages as c', 's.class_id', 'c.id')
            ->where('r.gander', 'Female') // Changed 'r.gander' to 'r.gender'
            ->where('s.session_id', $session)
            ->groupBy('c.class')
            ->selectRaw('c.class, COUNT(s.id) as count')
            ->pluck('count', 'class')
            ->toArray();



        $boysData = DB::table('students as s')
            ->join('registrations as r', 's.registration_id', 'r.id')
            ->join('class_manages as c', 's.class_id', 'c.id')
            ->where('r.gander', 'Male') // Changed 'r.gander' to 'r.gender'
            ->where('s.session_id', $session)
            ->groupBy('c.class')
            ->selectRaw('c.class, COUNT(s.id) as count')
            ->pluck('count', 'class')
            ->toArray();

        // Get all distinct classes
        $allClasses = DB::table('class_manages')
            // ->where('session_id', $session)
            ->pluck('class')->toArray();

        // Merge girls and boys counts for each class, inserting 0 for absent classes
        $girls = [];
        $boys = [];
        foreach ($allClasses as $class) {
            $girls[] = isset($girlsData[$class]) ? $girlsData[$class] : 0;
            $boys[] = isset($boysData[$class]) ? $boysData[$class] : 0;
        }
        $data['charts']['class'] = $allClasses;
        $data['charts']['girls'] = $girls;
        $data['charts']['boys'] = $boys;



        // Total number of students class wise
        $totalStudents = DB::table('students as s')
            ->join('registrations as r', 's.registration_id', 'r.id')
            ->join('class_manages as c', 's.class_id', 'c.id')
            ->where('s.session_id', $session)
            ->groupBy('c.class')
            ->selectRaw('c.class, COUNT(s.id) as count')
            ->pluck('count', 'class')
            ->toArray();

        $students = [];
        foreach ($allClasses as $class) {
            $students[] = isset($totalStudents[$class]) ? $totalStudents[$class] : 0;
        }
        $data['charts']['students'] = $students;

        // Registratered students
        $regStudents = DB::table('registrations as r')
            ->join('class_manages as c', 'r.class', '=', 'c.id')
            ->join('sessions as sn', 'r.session', '=', 'sn.id')
            ->leftJoin('sections as s', 'r.section', '=', 's.id')
            ->select(['r.id', 'r.name', 'r.dateOfBirth', 'r.fathersName', 'r.mobile', 'r.photo', 'r.created_at',  'c.class', 's.section'])
            ->where('sn.active', '1')
            ->where('r.status', '1')
            ->orderBy('r.id', 'desc')
            ->paginate(10);




        // Fees details ................
        $recentFees = DB::table('fees as f')
            ->leftJoin('exams as e', 'e.id', '=', 'f.exam_id')
            ->leftJoin('registrations as r', 'r.id', '=', 'f.s_id')
            ->leftJoin('class_manages as cm', 'cm.id', '=', 'r.class')
            ->where('f.session_id', '=', $session)
            ->select('f.*', 'e.exam_name','r.name','cm.class')
            ->orderBy('f.id', 'desc')
            ->paginate(10);

        return view('dashboard.admin_dashboard', compact('data', 'regStudents','recentFees'));
    }
}
