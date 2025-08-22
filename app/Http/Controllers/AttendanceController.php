<?php

namespace App\Http\Controllers;

use App\Jobs\SendFirebaseNotification;
use App\Models\attendance;
use App\Models\class_manage;
use App\Models\FingerPrint;
use App\Models\section;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    // ! Get the month according to the year.........
    public function getAttendanceYear(Request $request)
    {
        if ($request->has('year_id')) {
            // Fetches the month names according to the year
            $dateStrings = attendance::select('created_at')
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
        $dateStrings = attendance::select('created_at')->get();
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
            $studentsQuery = DB::table('attendances as at')
                ->join('registrations as r', 'at.registration_id', '=', 'r.id')
                ->join('students as st', 'r.id', '=', 'st.registration_id')
                ->join('class_manages as c', 'st.class_id', '=', 'c.id')
                ->leftJoin('users as u', 'at.attendance_by', '=', 'u.id')
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
                    'st.roll_no',
                ]);

            $studentsQuery->where('st.class_id', $request->class);

            // Check for selected section
            if ($request->has('section') && $request->section != null) {
                $studentsQuery->where('st.section_id', $request->section);
            }

            // Fetch students' attendance data
            $students = $studentsQuery->get();

            // Group data by name and roll no
            $groupedData = $students->groupBy(function ($item) {
                return $item->name . '-' . $item->roll_no; // Use a combination of name and roll no to group students
            });

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
                        'attendance_by' => $attendanceRecord->attendance_by ?? "Automatic", // Include the 'attendance_by' value
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

                    // Check if the day is Thursday and add 'S' to attendance
                    if ($currentDay->isThursday()) {
                        $key = collect($attendanceByDay)->search(function ($item) use ($day) {
                            return $item['day'] == $day;
                        });
                        if ($key !== false) {
                            $attendanceByDay[$key]['attendance'] = 'T';
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
                    'T' => $summaryCounts['T'] ?? 0,
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

        return view('attendance.attendance_view', compact('classes', 'sections', 'years', 'formattedData', 'daysInMonth', 'data'));
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
                ->leftJoin('attendances as at', function ($join) {
                    $join->on('r.id', '=', 'at.registration_id')
                        ->whereDate('at.created_at', Carbon::now()->format('Y-m-d'));
                })

                ->where('r.status', '=', '2')
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

        return view('attendance.attendance_fill', compact('classes', 'sections', 'students'));
    }



    //! Fill Attendance.....
    public function fill_attendnce(Request $request)
    {
        // If already existing for this request
        $attendance = attendance::where('registration_id', $request->registration_id)
            ->whereDate('updated_at', Carbon::now()->format('Y-m-d'))
            ->first();

        if ($attendance) { // if already attendance
            // $attendance->attendance_by = Auth::id();
            // $attendance->attendance_from = "Web";
            // $attendance->attendance_type = $request->attendance_type;
            // $attendance->attendance = $request->attendance;
            // if ($request->attendance == "A") {
            //     $attendance->inOutStatus = '0';
            //     $inOut = 'The student is now out of school. 💃💃💃';
            // } else {
            //     $attendance->inOutStatus = '1';
            //     $inOut = 'The student is now in school. 🏃‍♂️🏃‍♂️🏃‍♂️';
            // }
            // // $attendance->touch();
            // $attendance->save();
            // $savedAttendance = attendance::find($attendance->id);

            // // ! Send notificatin function --------------------------------
            // $notification = FirebasePushNotification(
            //     [$request->registration_id],
            //     "✅✅✅ Attendance Successfully",
            //     "$inOut",
            //     "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRglV4yCe1YieDU-3wottBOn1jOEbI4-LvE0A&s"
            // );

            // if (json_decode($notification)->status == true) {
            //     return response()->json([
            //         'status' => true,
            //         'notification' => true,
            //         'message' => 'Attendance updated successfully.',
            //         'attendance' => $savedAttendance->attendance,
            //         'time' => Carbon::parse($savedAttendance->updated_at)->format('d-m-Y h:i:s A'),
            //     ]);
            // } else {
            //     return response()->json([
            //         'status' => true,
            //         'notification' => false,
            //         'message' => 'Attendance updated successfully.',
            //         'attendance' => $savedAttendance->attendance,
            //         'time' => Carbon::parse($savedAttendance->updated_at)->format('d-m-Y h:i:s A'),
            //     ]);
            // }

            return response()->json([
                'status' => false,
                'notification' => false,
                'message' => 'Studenent already scaned !',
            ]);
        } else { // New Attendance;
            $attendance = new attendance;
            $attendance->registration_id = $request->registration_id;
            $attendance->session = $request->input('session');
            $attendance->class = $request->class;
            $attendance->section = $request->section;
            $attendance->roll = $request->roll;
            $attendance->attendance = $request->attendance;
            $attendance->attendance_by = Auth::id();
            $attendance->attendance_from = "Web";
            $attendance->attendance_type = $request->attendance_type;
            if ($request->attendance == "A") {
                $attendance->inOutStatus = '0';
                $inOut = 'The student is now out of school. 💃💃💃';
            } else {
                $attendance->inOutStatus = '1';
                $inOut = 'The student is now in school. 🏃‍♂️🏃‍♂️🏃‍♂️';
            }

            // ! Send notificatin function --------------------------------
            // $notification = FirebasePushNotification(
            //     [$request->registration_id],
            //     "✅✅✅ Attendance Successfully",
            //     "$inOut",
            //     "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRglV4yCe1YieDU-3wottBOn1jOEbI4-LvE0A&s"
            // );

            //Send Notification using queue

            dispatch(new SendFirebaseNotification(
                [$request->registration_id],
                "✅✅✅ Attendance Successfully",
                "$inOut",
                "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRglV4yCe1YieDU-3wottBOn1jOEbI4-LvE0A&s"
            ));

            $attendance->save();
            $savedAttendance = attendance::find($attendance->id);

            return response()->json([
                'status' => true,
                'notification' => true,
                'message' => 'Attendance successfully.',
                'attendance' => $savedAttendance->attendance,
                'time' => Carbon::parse($savedAttendance->updated_at)->format('d-m-y h:i:s A'),
            ]);

            // if (json_decode($notification)->status == true) {
            //     return response()->json([
            //         'status' => true,
            //         'notification' => true,
            //         'message' => 'Attendance successfully.',
            //         'attendance' => $savedAttendance->attendance,
            //         'time' => Carbon::parse($savedAttendance->updated_at)->format('d-m-Y h:i:s A'),
            //     ]);
            // }else{
            //     return response()->json([
            //         'status' => true,
            //         'notification' => false,
            //         'message' => 'Attendance successfully.',
            //         'attendance' => $savedAttendance->attendance,
            //         'time' => Carbon::parse($savedAttendance->updated_at)->format('d-m-Y h:i:s A'),
            //     ]);
            // }
        }
    }

    // ! Out Attendance
    public function getOutAttendance(Request $request)
    {
        // Retrieve the attendance for the given registration ID and date
        $attendance = attendance::where('registration_id', $request->registration_id)
            ->whereDate('updated_at', Carbon::now()->format('Y-m-d'))
            ->where('inOutStatus', '=', '1')
            ->first();

        if ($attendance !== null) {
            // Update the inOutStatus to 0 (student is out)
            $attendance->inOutStatus = 0;
            $attendance->save();

            dispatch(new SendFirebaseNotification(
                [$request->registration_id],
                "✅✅✅ Attendance Successfully",
                "Student Out from school",
                "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRglV4yCe1YieDU-3wottBOn1jOEbI4-LvE0A&s"
            ));
            return response()->json([
                'status' => true,
                'message' => 'Student out of school.',
                'attendance' => $attendance->attendance,
                'time' => Carbon::parse($attendance->updated_at)->format('d-m-Y h:i:s A'),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Student already out.',
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
                ->leftJoin('attendances as at', function ($join) {
                    $join->on('r.id', '=', 'at.registration_id')
                        ->whereDate('at.created_at', Carbon::now()->format('Y-m-d'));
                })

                ->where('r.status', '=', '2')
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

        return view('attendance.fingerprint_attendance', compact('classes', 'sections', 'students'));
    }

    // QR Attendance
    public function qr(Request $request)
    {
        return view('attendance.qr_attendance');
    }

    // QR Attendance
    public function out_qr(Request $request)
    {
        return view('attendance.out_qr_attendance');
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
}
