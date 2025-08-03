<?php

namespace App\Http\Controllers;

use App\Models\class_manage;
use App\Models\evening_attendance;
use App\Models\FingerPrint;
use App\Models\presentHosteller;
use App\Models\registration;
use App\Models\section;
use App\Models\HostelAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HostelAttendanceController extends Controller
{
    // ! Get the month according to the year.........
    public function getAttendanceYear(Request $request)
    {
        if ($request->has('year_id')) {
            // Fetches the month names according to the year
            $dateStrings = HostelAttendance::select('created_at')
                ->whereYear('created_at', $request->year_id)
                ->get();
            $months = [];

            foreach ($dateStrings as $dateObject) {
                $createdAt = Carbon::parse($dateObject->created_at);
                $months[] = $createdAt->format('F'); // Extract month name and add to the $months array
            }

            $uniqueMonths = array_unique($months);

            if (count($uniqueMonths) == 0) {
                return response()->json([
                    'status' => false,
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'months' => $uniqueMonths
                ]);
            }
        } else {
            // Handle if year_id is not provided in the request
            return response()->json([
                'status' => false,
                'message' => 'Year ID not provided.'
            ]);
        }
    }




    //! View Attendance.......
    public function index(Request $request)
    {
        // Fetches the year from the attendance table to view all the attendances
        $dateStrings = HostelAttendance::select('created_at')->get();
        $years = [];
        foreach ($dateStrings as $dateObject) {
            $createdAt = Carbon::parse($dateObject->created_at);
            $years[] = $createdAt->year; // Extract year and add to the $years array
        }
        $years = array_unique($years);

        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        // Current years session
        $classes = class_manage::get();

        // Get all sections according to classes
        $sections = DB::table('sections as s')->where('class_manages_id', $request->class)->select('s.id', 's.section')->get();

        //! Get all attendances
        if ($request->has('class') && $request->has('attendance_year') && $request->has('attendance_month')) {
            // Get the current year and month from the request
            $currentYear = $request->attendance_year;
            $currentMonth = $request->attendance_month;

            // get month number
            $monthNumber = Carbon::parse("1 $currentMonth")->format('m');

            // Get the number of days in the current month
            $daysInMonth = Carbon::createFromDate($currentYear, $monthNumber, 1)->endOfMonth()->day;

            // Database query to fetch attendance data based on criteria
            $studentsQuery = DB::table('hostel_attendances as at')
                ->join('registrations as r', 'at.registration_id', '=', 'r.id')
                ->join('students as st', 'r.id', '=', 'st.registration_id')
                ->join('class_manages as c', 'st.class_id', '=', 'c.id')
                ->join('users as u', 'at.attendance_by', '=', 'u.id')
                ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
                ->where('st.session_id', '=', $session)
                ->where('r.status', '=', '2')
                ->where('st.status', '=', '1')
                ->whereYear('at.updated_at', $currentYear)
                ->whereMonth('at.updated_at', $monthNumber)
                ->select([
                    'r.name',
                    'at.attendance',
                    'at.created_at',
                    'at.updated_at',
                    'r.id',
                    'c.class',
                    's.section',
                    'c.class',
                    'u.name as attendance_by',
                    'at.attendance_from',
                    'at.attendance_type',
                ]);

            $studentsQuery->where('st.class_id', $request->class);

            // Check for selected section
            if ($request->has('section') && $request->section != null) {
                $studentsQuery->where('st.section_id', $request->section);
            }

            // Fetch students' attendance data
            $students = $studentsQuery->get();

            // Group data by name
            $groupedData = $students->groupBy('name');

            // Reformat data to organize attendance by day inside each name
            $formattedData = [];
            foreach ($groupedData as $name => $attendanceData) {
                $attendanceByDay = [];
                foreach ($attendanceData as $attendanceRecord) {
                    $day = Carbon::parse($attendanceRecord->created_at)->format('d');
                    $attendanceByDay[] = [
                        'day' => $day,
                        'attendance' => $attendanceRecord->attendance,
                        'created_at' => $attendanceRecord->created_at, // Include the 'created_at' value
                        'attendance_by' => $attendanceRecord->attendance_by, // Include the 'attendance_by' value
                        'attendance_from' => $attendanceRecord->attendance_from, // Include the 'attendance_from' value
                        'attendance_type' => $attendanceRecord->attendance_type, // Include the 'attendance_type' value
                    ];
                }

                // Add all days of the month (1 to 31) with a default value "N"
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    $currentDay = Carbon::createFromDate($currentYear, $monthNumber, $day);
                    if (!collect($attendanceByDay)->contains('day', $day)) {
                        $attendanceByDay[] = [
                            'day' => $day,
                            'attendance' => 'N', // or any default value
                        ];
                    }

                    // Check if the day is Sunday and add 'S' to attendance
                    if ($currentDay->isSunday()) {
                        $key = collect($attendanceByDay)->search(function ($item) use ($day) {
                            return $item['day'] == $day;
                        });
                        if ($key !== false) {
                            $attendanceByDay[$key]['attendance'] = 'S';
                        }
                    }
                }

                // Sort attendance by day
                usort($attendanceByDay, function ($a, $b) {
                    return $a['day'] <=> $b['day'];
                });

                // Assign attendance data to the formatted array under the 'attendance' key
                $formattedData[$name]['attendance'] = $attendanceByDay;

                // Optionally, if you need the summary counts as well
                $summaryCounts = array_count_values(array_column($attendanceByDay, 'attendance'));
                $formattedData[$name]['summary_counts'] = [
                    'P' => $summaryCounts['P'] ?? 0,
                    'A' => $summaryCounts['A'] ?? 0,
                    'N' => $summaryCounts['N'] ?? 0,
                    'S' => $summaryCounts['S'] ?? 0,
                ];
            }

            $class = class_manage::select('class')->find($request->class);
            if ($request->has('section') && $request->section != '') {
                $section = section::select('section')->find($request->section);
                $section = $section->section;
            } else {
                $section = '';
            }

            // Return formatted year , month, class, section, and other data
            $data = [
                'year' => $request->attendance_year,
                'month' => $request->attendance_month,
                'class' => $class->class,
                'section' => $section,
            ];
        } else {
            // Handling the case where required parameters are missing
            $formattedData = '';
            $daysInMonth = ''; // Set a default value or handle it appropriately
            $data = [
                'year' => '',
                'month' => '',
                'section' => '',
                'class' => '',
            ];
        }

        return view('hostel.attendance_view', compact('classes', 'sections', 'years', 'formattedData', 'daysInMonth', 'data'));
    }


    //! Fill attendance
    public function fill(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->value('id');

        // Current years session classes
        $classes = class_manage::get();
        // Get all section according to classes
        $sections = DB::table('sections as s')->where('class_manages_id', $request->class)->select('s.id', 's.section')->get();

        // If selected classes
        if ($request->has('class') && $request->class != null) {
            $studentsQuery = DB::table('registrations as r')
                ->join('students as st', 'r.id', '=', 'st.registration_id')
                ->join('class_manages as c', 'st.class_id', '=', 'c.id')
                ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
                ->where('st.session_id', '=', $session)
                ->leftJoin('hostel_attendances as at', function ($join) {
                    $join->on('r.id', '=', 'at.registration_id')
                        ->whereDate('at.created_at', Carbon::now()->format('Y-m-d'));
                })

                ->where('r.status', '=', '2')
                ->where('r.hostel', '=', 'YES')
                ->where('st.status', '=', '1')
                ->select([
                    'at.attendance',
                    'at.updated_at',
                    'r.name',
                    'r.fathersName',
                    'r.id',
                    'c.class',
                    's.section',
                    'r.mobile',
                    'r.session',
                    'st.roll_no',
                    'st.class_id',
                ]);
            $studentsQuery->where('st.class_id', $request->class);
            //  If selected section.......................
            if ($request->has('section') && $request->section != null) {
                $studentsQuery->where('st.section_id', $request->section);
            }

            $students = $studentsQuery->get();
        } else {
            $students = '';
        }

        return view('hostel.attendance_fill', compact('classes', 'sections', 'students'));
    }



    //! In Attendance.....
    public function in_attendnce(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->value('id');

        //  check if the student is admit in hostel
        $isHosteller = registration::where('id', '=', $request->registration_id)
            ->where('session', '=', $session)
            ->first();
        if ($isHosteller->hostel == 'Yes') {
            // If already existing for this request
            $isExits = presentHosteller::where('registration_id', $request->registration_id)
                ->where('session', '=', $session)
                ->first();
            if ($isExits !== null) { // if already attendanceed
                if ($isExits->inOutStatus == '1') {
                    return response()->json([
                        'status' => false,
                        'notification' => false,
                        'message' => 'Studenent already in hostel !',
                    ]);
                } else {

                    // Update the status
                    $isExits->inOutStatus = '1';
                    $isExits->save();

                    // Store the students attendance
                    $newHosteller = new HostelAttendance;
                    $newHosteller->registration_id = $request->registration_id;
                    $newHosteller->session = $request->session;
                    $newHosteller->class = $request->class;
                    $newHosteller->section = $request->section;
                    $newHosteller->roll = $request->roll;
                    $newHosteller->attendance = "IN";
                    $newHosteller->attendance_by = Auth::id();
                    $newHosteller->attendance_from = "Web";
                    $newHosteller->attendance_type = $request->attendance_type;
                    $newHosteller->inOutStatus = '1';
                    $newHosteller->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'Student is back at hostel.',
                        'attendance' => $newHosteller->attendance,
                        'time' => Carbon::parse($newHosteller->updated_at)->format('d-m-Y h:i:s A'),
                    ]);
                }
            } else {
                // Store the students attendance
                $newHosteller = new presentHosteller;
                $newHosteller->registration_id = $request->registration_id;
                $newHosteller->session = $request->session;
                $newHosteller->class = $request->class;
                $newHosteller->section = $request->section;
                $newHosteller->roll = $request->roll;
                $newHosteller->attendance_by = Auth::id();
                $newHosteller->attendance_from = "Web";
                $newHosteller->attendance_type = $request->attendance_type;
                $newHosteller->inOutStatus = '1';
                $newHosteller->save();

                // Store the students attendance
                $attendance = new HostelAttendance();
                $attendance->registration_id = $request->registration_id;
                $attendance->session = $request->session;
                $attendance->class = $request->class;
                $attendance->section = $request->section;
                $attendance->roll = $request->roll;
                $attendance->attendance = "IN";
                $attendance->attendance_by = Auth::id();
                $attendance->attendance_from = "Web";
                $attendance->attendance_type = $request->attendance_type;
                $attendance->inOutStatus = '1';
                $attendance->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Student is back at hostel.',
                    'attendance' => $newHosteller->attendance,
                    'time' => Carbon::parse($newHosteller->updated_at)->format('d-m-Y h:i:s A'),
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Student is not hosteller.',
            ]);
        }
    }
    //! Out Attendance.....
    public function out_attendnce(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->value('id');

        //  check if the student is present in the hostel
        $isPresentInHostel = presentHosteller::where('registration_id', '=', $request->registration_id)
            ->where('session', '=', $session)
            ->first();
        if ($isPresentInHostel->inOutStatus == '1') {

            // update the students in out status
            $isPresentInHostel->inOutStatus = '0';
            $isPresentInHostel->save();

            // store the students out attendance

            $attendance = new HostelAttendance;
            $attendance->registration_id = $request->registration_id;
            $attendance->session = $request->session;
            $attendance->class = $request->class;
            $attendance->section = $request->section;
            $attendance->roll = $request->roll;
            $attendance->attendance = "OUT";
            $attendance->attendance_by = Auth::id();
            $attendance->attendance_from = "Web";
            $attendance->attendance_type = $request->attendance_type;
            $attendance->inOutStatus = '0';
            $attendance->save();

            return response()->json([
                'status' => true,
                'message' => 'Student is out of hostel.',
                'attendance' => $attendance->attendance,
                'time' => Carbon::parse($attendance->updated_at)->format('d-m-Y h:i:s A'),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Studenent already out of hostel !',
            ]);
        }
    }


    // ! fingerprint attendance
    public function fingerprint(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->value('id');

        // Current years session classes
        $classes = class_manage::get();
        // Get all section according to classes
        $sections = DB::table('sections as s')->where('class_manages_id', $request->class)->select('s.id', 's.section')->get();

        // If selected classes
        if ($request->has('class') && $request->class != null) {
            $studentsQuery = DB::table('registrations as r')
                ->join('students as st', 'r.id', '=', 'st.registration_id')
                ->join('class_manages as c', 'st.class_id', '=', 'c.id')
                ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
                ->where('st.session_id', '=', $session)
                ->leftJoin('hostel_attendances as at', function ($join) {
                    $join->on('r.id', '=', 'at.registration_id')
                        ->whereDate('at.created_at', Carbon::now()->format('Y-m-d'));
                })

                ->where('r.status', '=', '2')
                ->where('r.hostel', '=', 'YES')
                ->where('st.status', '=', '1')
                ->select([
                    'at.attendance',
                    'at.updated_at',
                    'r.name',
                    'r.fathersName',
                    'r.id',
                    'c.class',
                    's.section',
                    'r.mobile',
                    'r.session',
                    'st.roll_no'
                ]);
            $studentsQuery->where('st.class_id', $request->class);
            //  If selected section.......................
            if ($request->has('section') && $request->section != null) {
                $studentsQuery->where('st.section_id', $request->section);
            }

            $students = $studentsQuery->get();
        } else {
            $students = '';
        }

        return view('hostel.fingerprint_attendance', compact('classes', 'sections', 'students'));
    }

    //! QR Attendance
    public function in_qr_attendance(Request $request)
    {
        return view('hostel.qr_attendance');
    }
    public function out_qr_attendance(Request $request)
    {
        return view('hostel.out_qr_attendance');
    }


    // Attendance modal methods

    public function attendanceModal(Request $request)
    {
        $studentId = $request->input('studentId');
        $fingerIsExit = FingerPrint::where('student_id', $studentId)->first();
        $modal = '';

        if (empty($fingerIsExit)) {
            $modal .= '<div id="fingers_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="fullWidthModalLabel">Capture Finger Print</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-center">Student fingerprint is not available</h4>
                    </div>
                </div>
            </div>
        </div>';
        } else {
            $leftHandImagePath = asset('assets/img/left_hand.png');
            $rightHandImagePath = asset('assets/img/right_hand.png');
            $finger = asset('assets/img/finger.png');

            $modal .= '<div id="fingers_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="fullWidthModalLabel">Capture Finger Print</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="row text-center mt-3">
                                    <div class="col-3"><button onclick="getIsotemplate(' . "'$studentId'" . ',' . "'left_little'" . ');" class="btn btn-sm btn-success">Little</button>
                                    </div>
                                    <div class="col-2"><button onclick="getIsotemplate(' . "'$studentId'" . ',' . "'left_ring'" . ');" class="btn btn-sm btn-success">Ring</button>
                                    </div>
                                    <div class="col-2"><button onclick="getIsotemplate(' . "'$studentId'" . ',' . "'left_middle'" . ');" class="btn btn-sm btn-success">Middle</button>
                                    </div>
                                    <div class="col-2"><button onclick="getIsotemplate(' . "'$studentId'" . ',' . "'left_index'" . ');" class="btn btn-sm btn-success">Index</button>
                                    </div>
                                    <div class="col-3"><button onclick="getIsotemplate(' . "'$studentId'" . ',' . "'left_thumb'" . ');" class="btn btn-sm btn-success">Thumb</button>
                                    </div>
                                    <div class="col-12 text-center mt-3">
                                        <img class="img-fluid" src="' . $leftHandImagePath . '" alt="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 text-center mt-5 g-3">
                                <img height="170" width="110" id="scanning" class="img-fluid img-thumbnail"
                                src="' . $finger . '" alt="">
                            </div>
                            <div class="col-md-5 mt-3 g-3">
                                <div class="row text-center">
                                    <div class="col-3"><button onclick="getIsotemplate(' . "'$studentId'" . ',' . "'right_thumb'" . ');" class="btn btn-sm btn-success">Thumb</button>
                                    </div>
                                    <div class="col-2"><button onclick="getIsotemplate(' . "'$studentId'" . ',' . "'right_index'" . ');" class="btn btn-sm btn-success">Index</button>
                                    </div>
                                    <div class="col-2"><button onclick="getIsotemplate(' . "'$studentId'" . ',' . "'right_middle'" . ');" class="btn btn-sm btn-success">Middle</button>
                                    </div>
                                    <div class="col-2"><button onclick="getIsotemplate(' . "'$studentId'" . ',' . "'right_ring'" . ');" class="btn btn-sm btn-success">Ring</button>
                                    </div>
                                    <div class="col-3"><button onclick="getIsotemplate(' . "'$studentId'" . ',' . "'right_little'" . ');" class="btn btn-sm btn-success">Little</button>
                                    </div>
                                    <div class="col-12 text-center mt-3">
                                        <img class="img-fluid" src="' . $rightHandImagePath . '" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>';
        }


        return $modal;
    }


    // Evening attendance function
    public function evening()
    {
        return view('hostel.evening_attendance');
    }

    public function fill_evening_attendance(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->value('id');

        //  check if the student is admit in hostel
        $isHosteller = registration::where('id', '=', $request->registration_id)->first();


        if ($isHosteller->hostel == 'Yes') {

            // check the student if he is attend in hostel
            $isExits = presentHosteller::where('registration_id', $request->registration_id)
                ->where('session', '=', $session)
                ->first();
            if ($isExits->inOutStatus == '1') {
                // If already existing for this request
                $attendance = evening_attendance::where('registration_id', $request->registration_id)
                    ->whereDate('updated_at', Carbon::now()
                        ->format('Y-m-d'))
                    ->first();
                if ($attendance !== null) { // if already attendance
                    // $attendance->attendance_by = Auth::id();
                    // $attendance->attendance_from = "Web";
                    // $attendance->attendance_type = $request->attendance_type;
                    // $attendance->attendance = $request->attendance;
                    // $attendance->touch();
                    // $attendance->save();
                    // $savedAttendance = evening_attendance::find($attendance->id);

                    // return response()->json([
                    //     'status' => true,
                    //     'message' => 'Attendance updated successfully.',
                    //     'attendance' => $savedAttendance->attendance,
                    //     'time' => Carbon::parse($savedAttendance->updated_at)->format('d-m-Y h:i:s A'),
                    // ]);

                    return response()->json([
                        'status' => false,
                        'notification' => false,
                        'message' => 'Studenent already scaned !',
                    ]);
                } else { // New Attendance;
                    $attendance = new evening_attendance;
                    $attendance->registration_id = $request->registration_id;
                    $attendance->session = $request->session;
                    $attendance->class = $request->class;
                    $attendance->section = $request->section;
                    $attendance->roll = $request->roll;
                    $attendance->attendance = $request->attendance;
                    $attendance->attendance_by = Auth::id();
                    $attendance->attendance_from = "Web";
                    $attendance->attendance_type = $request->attendance_type;
                    // $attendance->inOutStatus = '1';
                    $attendance->save();
                    $savedAttendance = evening_attendance::find($attendance->id);

                    return response()->json([
                        'status' => true,
                        'message' => 'Attendance successfully.',
                        'attendance' => $savedAttendance->attendance,
                        'time' => Carbon::parse($savedAttendance->updated_at)->format('d-m-Y h:i:s A'),
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Student at home.',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Student is not hosteller.',
            ]);
        }
    }


    // Done evening attendances
    public function eveningAttendanceDone(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Current years session
        $classes = class_manage::get();

        $students = DB::table('registrations as r')
            ->join('students as st', 'r.id', '=', 'st.registration_id')
            ->join('class_manages as c', 'st.class_id', '=', 'c.id')
            ->join('sessions as ss', 'st.session_id', '=', 'ss.id')
            ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
            ->leftJoin('evening_attendance as ea', 'ea.registration_id', '=', 'r.id')
            ->where('st.session_id', '=', $session)
            ->whereDate('ea.created_at', Carbon::now()->format('Y-m-d'))
            // ->where('r.status', '=', '2')
            // ->where('st.status', '=', '1')
            // ->where('r.hostel', '=', 'YES')
            ->select(['r.id', 'r.name', 'r.fathersName', 'r.mobile', 'r.dateOfBirth', 'r.photo', 'c.class', 's.section', 'ss.session', 'st.roll_no', 'st.status']);


        // if ($request->class != null) {
        //     $students = $students->where('st.class_id', $request->class);
        // } else {
        //     // if class is not specified
        //     $students = $students->where('st.class_id', $classes[0]->id);
        // }

        if ($request->name != null) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('r.name', 'LIKE', '%' . $request->name . '%');
            });
        }
        if ($request->father_name != null) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('r.fathersName', 'LIKE', '%' . $request->father_name . '%');
            });
        }
        if ($request->mobile != null) {
            $students = $students->where('r.mobile', 'LIKE', '%' . $request->mobile . '%');
        }
        $students = $students->paginate(10);
        return view('hostel.evening_attendance_done', compact('students', 'classes'));
    }


    // Left student data for evening students
    public function eveningAttendanceLeft(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Current year's session
        $classes = class_manage::get();

        // Query for students who are not present in evening_attendance today
        $students = DB::table('registrations as r')
            ->join('students as st', 'r.id', '=', 'st.registration_id')
            ->join('class_manages as c', 'st.class_id', '=', 'c.id')
            ->join('sessions as ss', 'st.session_id', '=', 'ss.id')
            ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
            ->leftJoin('present_hostellers as ph', 'ph.registration_id', '=', 'r.id')
            ->leftJoin('evening_attendance as ea', function ($join) {
                $join->on('ea.registration_id', '=', 'r.id')
                    ->whereDate('ea.created_at', Carbon::now()->format('Y-m-d'));
            })
            ->where('st.session_id', '=', $session)
            ->whereNull('ea.id') // Students not in evening_attendance
            ->where('r.status', '=', '2')
            ->where('st.status', '=', '1') // Students status
            ->where('ph.inOutStatus', '=', '1') // Students present in hostel
            ->where('r.hostel', '=', 'YES')
            ->select(['r.id', 'r.name', 'r.fathersName', 'r.mobile', 'r.dateOfBirth', 'r.photo', 'c.class', 's.section', 'ss.session', 'st.roll_no', 'st.status']);

        // Filter by class if specified
        // if ($request->class != null) {
        //     $students = $students->where('st.class_id', $request->class);
        // } else {
        //     // If class is not specified, use the first class from the session
        //     $students = $students->where('st.class_id', $classes[0]->id);
        // }

        // Filter by student name if specified
        if ($request->name != null) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('r.name', 'LIKE', '%' . $request->name . '%');
            });
        }

        // Filter by father's name if specified
        if ($request->father_name != null) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('r.fathersName', 'LIKE', '%' . $request->father_name . '%');
            });
        }

        // Filter by mobile number if specified
        if ($request->mobile != null) {
            $students = $students->where('r.mobile', 'LIKE', '%' . $request->mobile . '%');
        }

        // Paginate the results
        $students = $students->paginate(10);

        // Return the view with the students and class data
        return view('hostel.evening_attendance_left', compact('students', 'classes'));
    }


    // IN Out Record
    public function inOutRecord(Request $request){
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Current years session
        $classes = class_manage::get();

        $students = DB::table('registrations as r')
            ->join('students as st', 'r.id', '=', 'st.registration_id')
            ->join('class_manages as c', 'st.class_id', '=', 'c.id')
            ->join('sessions as ss', 'st.session_id', '=', 'ss.id')
            ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
            ->leftJoin('hostel_attendances as ha', 'ha.registration_id', '=', 'r.id')
            ->where('st.session_id', '=', $session)
            ->where('r.status', '=', '2')
            ->where('st.status', '=', '1')
            ->where('r.hostel', '=', 'YES')
            ->select(['r.id', 'r.name', 'r.fathersName', 'r.mobile', 'ha.created_at', 'r.photo', 'c.class', 's.section', 'ss.session', 'st.roll_no', 'ha.attendance'])
            ->orderBy('r.name')
            ->orderBy('ha.created_at');


        if ($request->class != null) {
            $students = $students->where('st.class_id', $request->class);
        }else {
            // if class is not specified
            $students = $students->where('st.class_id', $classes[0]->id);
        }

        if ($request->name != null) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('r.name', 'LIKE', '%' . $request->name . '%');
            });
        }
        if ($request->father_name != null) {
            $students = $students->where(function ($query) use ($request) {
                $query->where('r.fathersName', 'LIKE', '%' . $request->father_name . '%');
            });
        }
        if ($request->mobile != null) {
            $students = $students->where('r.mobile', 'LIKE', '%' . $request->mobile . '%');
        }
        $students = $students->paginate(10);
        return view('hostel.inOutRecord', compact('students', 'classes'));
    }
}
