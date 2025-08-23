<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendFirebaseNotification;
use App\Models\attendance;
use App\Models\class_manage;
use App\Models\registration;
use App\Models\section;
use App\Models\student_leave;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // ! Url: http://127.0.0.1:8000/api/student/profile/5
    public function index(Request $request)
    {
        if ($request->id == '') {
            return response()->json([
                'status' => false,
                'data' => 'User ID not provided',
            ], 404);
        }

        // Current session
        $session = DB::table('sessions')->where('active', '1')->first();
        if (!$session) {
            return response()->json([
                'status' => false,
                'data' => 'Active session not found',
            ], 404);
        }

        $students = DB::table('students as s')
            ->join('class_manages as c', 's.class_id', '=', 'c.id')
            ->leftJoin('sections as sc', 's.section_id', '=', 'sc.id')
            ->join('registrations as r', 's.registration_id', '=', 'r.id')
            ->where('s.status', '1')
            ->where('s.session_id', $session->id)
            ->where('s.registration_id', $request->id)
            ->select('r.*', 'c.class', 'sc.section', 's.roll_no')
            ->first();

        $students->registration_prefix = config('website.registration');
        $students->academic_year = $session->session;

        if (!$students) {
            return response()->json([
                'status' => false,
                'data' => 'User not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'path' => url('uploads/images/registration/'),
            'data' => $students,
        ], 200);
    }

    // ! Get all Secions
    // ! URL : http://localhost:8000/api/session
    public function session()
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get(['id', 'session']);

        if ($session) {
            return response()->json([
                'status' => true,
                'session' => $session,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'session' => '',
            ], 200);
        }
    }
    // ! Get all classes
    // ! URL : http://localhost:8000/api/class
    public function class()
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->value('id');
        // Current years session classes
        $classes = class_manage::where('session_id', $session)->get(['id', 'class']);

        if ($classes->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'class' => $classes,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'class' => '',
            ], 200);
        }
    }


    //! Get section
    // ! URL : http://localhost:8000/api/section/5
    public function section($id)
    {
        // Get all section according to classes
        $sections = DB::table('sections as s')->where('class_manages_id', $id)->select('s.id', 's.section')->get();

        if (!empty($sections)) {
            return response()->json([
                'status' => true,
                'section' => $sections,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'section' => '',
            ], 200);
        }
    }

    //! URL:: http://127.0.0.1:8000/api/student/attendance
    // Students attendants................
    public function attendance(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'class' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
            ], 200);
        }

        // If selected classes
        if ($request->has('class') && $request->class != null) {
            $studentsQuery = DB::table('registrations as r')
                ->join('class_manages as c', 'r.class', '=', 'c.id')
                ->join('students as st', 'r.id', '=', 'st.registration_id')
                ->leftJoin('attendances as at', function ($join) {
                    $join->on('r.id', '=', 'at.registration_id')
                        ->whereDate('at.created_at', Carbon::now()->format('Y-m-d'));
                })
                ->leftJoin('sections as s', 'r.section', '=', 's.id')
                ->join('sessions as sn', 'r.session', '=', 'sn.id')
                ->where('sn.active', '1')
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
            $studentsQuery->where('c.id', $request->class);
            //  If selected section.......................
            if ($request->has('section') && $request->section != null) {
                $studentsQuery->where('s.id', $request->section);
            }
            $data['students'] = $studentsQuery->get();
        } else {
            $data['students'] = [];
        }

        // Return the data
        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }




    // Students attendance fill -----------Post Methods
    // ! URL : http://127.0.0.1:8000/api/student/attendance/fill
    // Body Data :
    // registration_id: registration_id,
    // session: session,
    // class: className,
    // section: section ?? '',
    // roll: roll,
    // attendance: value,
    // attendance_type : QR,
    // attendance_by : 'user_id',

    public function ateendanceFill(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'registration_id' => 'required',
                'session' => 'required',
                'class' => 'required',
                'roll' => 'required',
                'attendance' => 'required',
                'attendance_by' => 'required',
                'attendance_type' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
            ], 200);
        }

        // If already existing for this request
        $attendance = attendance::where('registration_id', $request->registration_id)
            ->whereDate('updated_at', Carbon::now()->format('Y-m-d'))
            ->first();
        if ($attendance !== null) { // if already attendance
            $attendance->attendance_by = $request->attendance_by;
            $attendance->attendance_from = "App";
            $attendance->attendance_type = $request->attendance_type;
            $attendance->attendance = $request->attendance;
            $attendance->touch();
            $attendance->save();
            $savedAttendance = attendance::find($attendance->id);

            return response()->json([
                'status' => true,
                'message' => 'Attendance updated successfully.',
                'attendance' => $savedAttendance->attendance,
                'time' => Carbon::parse($savedAttendance->updated_at)->format('d-m-Y h:i:s A'),
            ]);
        } else { // New Attendance;
            $attendance = new attendance;
            $attendance->registration_id = $request->registration_id;
            $attendance->session = $request->input('session');
            $attendance->class = $request->class;
            $attendance->section = $request->section;
            $attendance->roll = $request->roll;
            $attendance->attendance = $request->attendance;
            $attendance->attendance_by = $request->attendance_by;
            $attendance->attendance_from = "App";
            $attendance->attendance_type = $request->attendance_type;
            $attendance->inOutStatus = '1';
            $attendance->save();
            $savedAttendance = attendance::find($attendance->id);

            return response()->json([
                'status' => true,
                'message' => 'Attendance successfully.',
                'attendance' => $savedAttendance->attendance,
                'time' => Carbon::parse($savedAttendance->updated_at)->format('d-m-Y h:i:s A'),
            ]);
        }
    }


    // ! Attendance view
    // ! URL : http://127.0.0.1:8000/api/student/attendance/view?attendance_year=2023&attendance_month=December&class=1&section=&roll=2
    public function ateendanceView(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // 'attendance_year' => 'required',
                'attendance_month' => 'required',
                'class' => 'required',
                'roll' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
            ], 200);
        }

        // Fetches the year from the attendance table to view all the attendances
        $dateStrings = attendance::select('created_at')->get();
        $years = [];

        foreach ($dateStrings as $dateObject) {
            $createdAt = Carbon::parse($dateObject->created_at);
            $years[] = $createdAt->year; // Extract year and add to the $years array
        }
        $years = array_unique($years);

        // Current session
        $session = DB::table('sessions')->where('active', '1')->first()->id;

        // Current years session
        $classes = class_manage::where('session_id', $session)->get();

        // Get all sections according to classes
        $sections = DB::table('sections as s')->where('class_manages_id', $request->class)->select('s.id', 's.section')->get();

        //! Get all attendances
        if ($request->has('class') && $request->has('attendance_month')) {
            // Get the current year and month from the request
            $currentYear = Carbon::now()->year;
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
            $studentsQuery->where('st.roll_no', $request->roll);

            // Fetch students' attendance data
            $students = $studentsQuery->get();

            // Reformat data to organize attendance by day inside each name
            $formattedData = [];
            foreach ($students as $attendanceRecord) {
                $day = Carbon::parse($attendanceRecord->created_at)->format('d');
                $shortDay = Carbon::parse($attendanceRecord->created_at)->format('D');
                $formattedData[] = [
                    'day' => $day,
                    'shortDay' => $shortDay,
                    'attendance' => $attendanceRecord->attendance,
                    'updated_at' => Carbon::parse($attendanceRecord->updated_at)->format('h:i A'),
                    'created_at ' => Carbon::parse($attendanceRecord->created_at)->format('h:i A'),
                    'attendance_by' => $attendanceRecord->attendance_by,
                    'attendance_from' => $attendanceRecord->attendance_from,
                    'attendance_type' => $attendanceRecord->attendance_type,
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

        $StudentData = [
            'years' => $years,
            // 'classes' => $classes,
            // 'sections' => $sections,
            'attendance' => $formattedData, // Only include attendance data
            'daysInMonth' => $daysInMonth,
            'data' => $data
        ];

        return response()->json([
            'status' => true,
            'data' => $StudentData
        ], 200);
    }



    // ! Student Login Function
    //! URL : http://127.0.0.1:8000/api/student/login
    public function login(Request $request)
    {
        // Begin a database transaction
        DB::beginTransaction();

        try {
            // Current session
            $session = DB::table('sessions')->where('active', '1')->value('id');

            $validator = Validator::make(
                $request->all(),
                [
                    'mobile' => 'required',
                    'dob' => 'required',
                    'device_token' => 'required',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'data' => $validator->errors(),
                ], 200);
            }

            $dob = Carbon::createFromFormat('dmY', $request->dob)->format('d-m-Y');

            $user = DB::table('registrations as r')
                ->join('students as s', 's.registration_id', 'r.id')
                ->where('mobile', $request->mobile)
                ->where('dateOfBirth', $dob)
                ->where('s.session_id', $session)
                ->where('s.status', '1')
                ->get([
                    'r.id as registration_id',
                    's.session_id',
                    's.class_id',
                    's.section_id',
                    's.roll_no',
                    'r.name',
                    'r.fathersName',
                    'r.mobile',
                    'r.photo',
                    'r.role'
                ]);

            if ($user->isNotEmpty()) {
                // Check if device token already exists for the registration ID
                $existingToken = DB::table('device_token')
                    ->where('registration_id', $user[0]->registration_id)
                    ->value('id');

                // If device token already exists, delete it
                if ($existingToken) {
                    DB::table('device_token')->where('registration_id', $user[0]->registration_id)->delete();
                }

                // Insert the new device token
                DB::table('device_token')->insert([
                    'registration_id' => $user[0]->registration_id,
                    'device_token' => $request->device_token,
                ]);

                // Commit the transaction
                DB::commit();

                $user['registration_prefix'] = config('website.registration');
                return response()->json([
                    'status' => true,
                    'data' => $user,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'error' => 'Invalid credentials or student blocked.',
                ], 401);
            }
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollback();

            // Handle the exception (e.g., log it)
            return response()->json([
                'status' => false,
                'error' => 'An error occurred while processing your request.',
            ], 500);
        }
    }


    //! Student result
    public function singleResult(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'session' => 'required',
                'class' => 'required',
                'roll' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
            ], 400);
        }

        $exams = DB::table('exams as ex')
            ->join('class_manages as c', 'c.id', 'ex.class')
            ->leftJoin('sections as s', 's.id', 'ex.section')
            ->where('ex.session_id', '=', $request->input('session'))
            ->where('ex.class', '=', $request->class)
            ->where('ex.status', '=', '1')
            ->where('ex.is_published', '=', '1')
            ->select(
                'ex.id',
                'ex.exam_name',
                'c.class'
            )
            ->orderBy('ex.id', 'desc')
            ->get();

        $groupedResults = [];

        foreach ($exams as $exam) {
            $studentResults = DB::table('add_marks as am')
                ->join('exams_subjects as es', 'am.subject_id', 'es.id')
                ->where('am.exam_id', $exam->id)
                ->where('am.roll_no', $request->roll)
                ->select(
                    'am.full_marks',
                    'es.oral_marks',
                    'am.marks_obtained',
                    'am.oral_marks_obtained',
                    'es.pass_marks',
                    'es.subject',
                    'es.exam_date',
                    'es.subjectType'
                )
                ->get();

            $totalMarks = 0;
            $totalMarksObtained = 0;
            $subjectGrades = [];

            foreach ($studentResults as $result) {
                $written = intval($result->marks_obtained ?? 0);
                $oral = intval($result->oral_marks_obtained ?? 0);

                $writtenFull = intval($result->full_marks ?? 0);
                $oralFull = intval($result->oral_marks ?? 0);

                $totalSubjectMarks = $writtenFull + $oralFull;
                $totalSubjectObtained = $written + $oral;

                $totalMarks += $totalSubjectMarks;
                $totalMarksObtained += $totalSubjectObtained;

                $percentage = ($totalSubjectMarks > 0)
                    ? ($totalSubjectObtained / $totalSubjectMarks) * 100
                    : 0;

                if ($percentage >= 90) {
                    $grade = 'AA';
                } elseif ($percentage >= 80) {
                    $grade = 'A+';
                } elseif ($percentage >= 60) {
                    $grade = 'A';
                } elseif ($percentage >= 45) {
                    $grade = 'B+';
                } elseif ($percentage >= 35) {
                    $grade = 'B';
                } elseif ($percentage >= 25) {
                    $grade = 'C';
                } else {
                    $grade = 'D';
                }

                $subjectGrades[$result->subject] = [
                    'grade' => $grade,
                    'written_marks' => $written,
                    'oral_marks' => $oral,
                    'written_full_marks' => $writtenFull,
                    'oral_full_marks' => $oralFull,
                    'total_subject_marks' => $totalSubjectMarks,
                    'total_subject_obtained' => $totalSubjectObtained,
                    'exam_date' => $result->exam_date,
                    'subject_type' => $result->subjectType
                ];
            }

            $percentage = ($totalMarks > 0)
                ? ($totalMarksObtained / $totalMarks) * 100
                : 0;
            $percentage = number_format($percentage, 2);

            $overallGrade = 'F';
            if ($percentage >= 90) {
                $overallGrade = 'AA';
            } elseif ($percentage >= 80) {
                $overallGrade = 'A+';
            } elseif ($percentage >= 60) {
                $overallGrade = 'A';
            } elseif ($percentage >= 45) {
                $overallGrade = 'B+';
            } elseif ($percentage >= 35) {
                $overallGrade = 'B';
            } elseif ($percentage >= 25) {
                $overallGrade = 'C';
            } elseif ($percentage < 25) {
                $overallGrade = 'D';
            }

            $groupedResults[$exam->exam_name] = [
                'total_marks' => $totalMarks,
                'marks_obtained' => $totalMarksObtained,
                'percentage' => $percentage,
                'grade' => $overallGrade,
                'subject_grades' => $subjectGrades,
                'results' => $studentResults->toArray(),
            ];
        }

        $data = [];
        foreach ($groupedResults as $examName => $result) {
            $data[] = [$examName => $result];
        }

        return response()->json([
            'status' => true,
            'data' => $data,
        ], 200);
    }



    // Student Fees details
    public function feesDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registration_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
            ], 400);
        }

        if ($validator->passes()) {
            // Current session
            $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

            $feesData = DB::table('fees as f')
                ->leftJoin('exams as e', 'e.id', 'f.exam_id')
                ->where('f.s_id', '=', $request->registration_id)
                ->where('f.session_id', '=', $session)
                ->select(
                    'f.fees_type',
                    'f.month',
                    'f.amount',
                    'f.status',
                    'f.receiver',
                    'f.remarks',
                    'e.exam_name',
                    DB::raw('DATE_FORMAT(f.created_at, "%d-%m-%Y / %h:%i %p") as created_at'),
                )
                ->orderByRaw("FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
                ->orderBy('f.created_at', 'desc')
                ->get();

            // Group data by month
            $groupedData = $feesData->groupBy(function ($item) {
                return $item->month ?: 'Exam Fees';
            });

            // Convert grouped data to array
            $monthlyData = [];
            foreach ($groupedData as $month => $data) {
                $monthlyData[] = [
                    $month => $data->toArray()
                ];
            }

            return response()->json([
                'status' => true,
                'data' => $monthlyData,
            ], 200);
        }
    }





    // ! Student Leave function
    public function leave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session' => 'required',
            'class' => 'required',
            'roll' => 'required',
            'name' => 'required',
            'reasons' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            // 'letterName' => 'required|mimes:jpeg,jpg,png,JPG,JPEG,PNG|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
            ], 400);
        }

        try {
            // Begin a transaction
            DB::beginTransaction();

            // Upload the file
            $letter = $request->file('letterName');
            $ext = $letter->getClientOriginalExtension();
            $letterName = time() . '.' . $ext;
            $letter->move(public_path('uploads/images/leave'), $letterName);

            // Save the leave record
            $leave = new student_leave;
            $leave->name = $request->name;
            $leave->session = $request->input('session');
            $leave->class = $request->class;
            $leave->section = $request->section;
            $leave->roll = $request->roll;
            $leave->reasons = $request->reasons;
            $leave->from_date = $request->from_date;
            $leave->to_date = $request->to_date;
            // $leave->letterName = $letterName;
            $leave->save();

            // Commit the transaction
            DB::commit();

            // Send notification to mobile user
            dispatch(new SendFirebaseNotification(
                [$request->registration_id],
                "Dear {$request->name}",
                "Your leave added successfully.",
                "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRwBeDUheKShh9OK2Vg6OjJrnPPUaXpN5LNCA&s"
            ));

            return response()->json([
                'status' => true,
                'message' => 'Leave sent successfully.',
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction on exception
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => 'Error sending leave: ' . $e->getMessage(),
            ], 500);
        }
    }


    // ! Student leavedata --------------------------------
    public function leavedata(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'session' => 'required',
            'class' => 'required',
            'roll' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
            ], 400);
        }

        $leavedata = DB::table('student_leaves as sl')
            ->join('class_manages as cm', 'sl.class', '=', 'cm.id')
            ->leftJoin('sections as s', 'sl.section', '=', 's.id')
            ->where('sl.session', $request->input('session'))
            ->where('sl.class', $request->class)
            ->where('sl.roll', $request->roll)
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
                'sl.isApproved',
            )
            ->orderBy('sl.id', 'desc')
            ->get();


        return response()->json([
            'status' => true,
            'data' => $leavedata,
        ], 200);
    }


    //! Student inOut and Attendance percentage -------------------------------------------------

    public function inOutAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session' => 'required',
            'class' => 'required',
            'roll' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
            ], 400);
        }

        // InOut Status
        $inOut = DB::table('attendances as at');
        if ($request->section != null) {
            $inOut->where('at.section', $request->section);
        }
        $inOut = $inOut->where('at.session', $request->input('session'))
            ->where('at.class', $request->class)
            ->where('at.roll', $request->roll)
            ->whereDate('at.created_at', date('Y-m-d'))
            ->select('at.inOutStatus')
            ->first();

        // If $inOut is null, set default value for inOutStatus
        $inOutStatus = $inOut ? $inOut->inOutStatus : '0';

        // Attendance Percentage
        $attendancePercentage = DB::table('attendances as at');
        if ($request->section != null) {
            $attendancePercentage->where('at.section', $request->section);
        }
        $attendanceData = $attendancePercentage->where('at.session', $request->input('session'))
            ->where('at.class', $request->class)
            ->where('at.roll', $request->roll)
            ->whereMonth('at.created_at', date('m'))
            ->select('at.attendance')
            ->get();

        $totalP = 0;
        $totalA = 0;

        foreach ($attendanceData as $attendance) {
            if ($attendance->attendance === "P") {
                $totalP++;
            } elseif ($attendance->attendance === "A") {
                $totalA++;
            }
        }

        $totalCount = count($attendanceData);

        // Calculate percentage
        if ($totalCount > 0) {
            $percentageP = intval(($totalP / $totalCount) * 100);
        } else {
            $percentageP = 0;
        }

        return response()->json([
            'status' => true,
            'data' => [
                'inOutStatus' => $inOutStatus,
                'percentageP' => $percentageP,
            ],
        ], 200);
    }

    // ! Student Logout Function

    public function appLogout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registration_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'data' => $validator->errors(),
            ], 400);
        }

        // Delete the device token from device_token table
        $student = DB::table('device_token')
            ->where('registration_id', '=', $request->registration_id)
            ->delete();

        if ($student) {
            return response()->json([
                'status' => true,
                'data' => ['Student logout.'],
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'data' => ['Student not found.'],
            ], 200);
        }
    }
}
