<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\exam;
use App\Models\addMark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ManageExamController extends Controller
{
    public function index(Request $request)
    {
        // Current year session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $exams = DB::table('exams as ex')
            ->join('class_manages as c', 'c.id', 'ex.class')
            ->leftJoin('sections as s', 's.id', 'ex.section')
            ->where('ex.session_id', '=', $session)
            ->where('ex.status', '=', '1')
            ->where('ex.is_published', '=', '0')
            ->select('ex.id', 'ex.exam_name', 'c.class', 's.section')
            ->orderBy('ex.id', 'desc')
            ->get();

        // Get the all subjects for select box
        $subjects = DB::table('exams_subjects as s')
            ->where('exam_id', '=', $request->exams)
            ->select('s.id', 's.subject')
            ->get();


        if ($request->get('exams') && $request->exams !== '' && $request->get('subject') && $request->subject !== '') {
            $exam_id = $request->input('exams');
            $subject = $request->input('subject');

            $students = DB::table('exams as ex')
                ->join('exams_subjects as es', 'es.exam_id', 'ex.id')
                ->join('class_manages as c', 'c.id', 'ex.class')
                ->join('students as st', 'st.class_id', 'ex.class')
                ->join('registrations as r', 'st.registration_id', 'r.id')
                // ->leftJoin('sections as s', 's.id', 'st.section_id')
                ->leftJoin('sections as s', function ($join) {
                    $join->on('s.id', '=', 'st.section_id')
                        ->on('s.id', '=', 'ex.section');
                })
                ->leftJoin('add_marks as am', function ($join) {
                    $join->on('am.registration_id', '=', 'r.id')
                        ->on('am.subject_id', '=', 'es.id');
                })
                ->select(
                    'r.id as registration_id',
                    'r.session',
                    'r.name',
                    'st.roll_no',
                    'c.id as class_id',
                    'c.class',
                    's.id as section_id',
                    's.section',
                    'es.full_marks',
                    'es.oral_marks',
                    'am.oral_marks_obtained',
                    'ex.exam_name',
                    'es.id',
                    'es.subject',
                    'am.marks_obtained',
                    'ex.id as exam_id'
                )
                ->where('ex.id', $exam_id)
                ->where('es.id', $subject)
                ->where('st.session_id', '=', $session)
                ->where('ex.status', '=', '1')
                ->where('st.status', '=', '1')
                ->where('ex.is_published', '=', '0')
                ->orderBy('st.roll_no', 'asc')
                ->get();
        } else {
            $students = [];
        }
        return view('manage_exam.add_marks', compact('exams', 'subjects', 'students'));
    }



    // Get a list of subjects according to exams
    public function get_exam_subject(Request $request)
    {
        if ($request->exam_id != null) {
            $subject = DB::table('exams_subjects as es')
                ->where('es.exam_id', $request->exam_id)
                ->select('es.id as subject_id', 'es.subject')
                ->get();
            if ($subject->count() == 0) {
                return response()->json([
                    'status' => false,
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'subject' => $subject
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Please select a exam.'
            ]);
        }
    }

    // Add marks
    public function add_marks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registration_id.*' => 'required|integer',
            'session.*' => 'required|integer',
            'name.*' => 'required|string',
            'class.*' => 'required|string',
            'section.*' => 'string|nullable',
            'roll_no.*' => 'required',
            'exam_name.*' => 'required|string',
            'subject.*' => 'required|string',
            'full_marks.*' => 'required',
            'marks_obtained.*' => 'required',
            'oral_marks.*' => 'required',
            'oral_marks_obtained.*' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 422);
        }

        // Validation passed
        for ($i = 0; $i < count($request['registration_id']); $i++) {

            $mark = addMark::where('subject_id', $request['subject'][$i])
                ->where('exam_id', $request['exam_name'][$i])
                ->where('roll_no', $request['roll_no'][$i])
                ->first();

            if ($mark) {
                $mark->marks_obtained = $request['marks_obtained'][$i];
                $mark->oral_marks_obtained = $request['oral_marks_obtained'][$i];
                $mark->save();
            } else {
                $mark = new addMark;
                $mark->registration_id = $request['registration_id'][$i];
                $mark->session = $request['session'][$i];
                $mark->name = $request['name'][$i];
                $mark->class = $request['class'][$i];
                $mark->section = $request['section'][$i];
                $mark->roll_no = $request['roll_no'][$i];
                $mark->exam_id = $request['exam_name'][$i];
                $mark->subject_id = $request['subject'][$i];
                $mark->full_marks = $request['full_marks'][$i];
                $mark->marks_obtained = $request['marks_obtained'][$i];
                $mark->oral_marks = $request['oral_marks'][$i];
                $mark->oral_marks_obtained = $request['oral_marks_obtained'][$i];
                $mark->save();
            }
        }

        session()->flash('success', 'Marks added successfully.');
        return response()->json([
            'status' => true,
            'message' => 'Marks added successfully.'
        ], 200);
    }



    public function result(Request $request)
    {
        // Current year session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        // get Exams
        $exams = DB::table('exams as ex')
            ->join('class_manages as c', 'c.id', 'ex.class')
            ->leftJoin('sections as s', 's.id', 'ex.section')
            ->where('ex.session_id', '=', $session)
            ->where('ex.status', '=', '1')
            ->where('ex.is_published', '=', '1')
            ->select('ex.id', 'ex.exam_name', 'c.class', 's.section')
            ->orderBy('ex.id', 'desc')
            ->get();

        // Get all subjects for select box
        $subjects = DB::table('exams_subjects as s')
            ->where('exam_id', '=', $request->exams)
            ->select('s.id', 's.subject')
            ->get();

        //! When select exams and subject
        if ($request->get('exams') && $request->exams !== '' && $request->get('subject') && $request->subject !== '') {
            $subject_id = $request->input('subject');
            $exam_id = $request->input('exams');

            // Fetch the data
            $studentExamSubject = DB::table('add_marks as am')
                ->join('class_manages as cm', 'am.class', 'cm.id')
                ->leftJoin('sections as s', 'am.section', 's.id')
                ->join('exams_subjects as es', 'am.subject_id', 'es.id')
                ->select(
                    'am.name',
                    'es.full_marks',
                    'am.marks_obtained',
                    'es.oral_marks',
                    'am.oral_marks_obtained',
                    'am.roll_no',
                    'cm.class',
                    's.section',
                    'es.pass_marks',
                    'es.subject'
                )
                ->where('am.exam_id', $exam_id)
                ->where('am.subject_id', $subject_id)
                ->orderBy('am.marks_obtained', 'desc')
                ->get();

            // send blank
            $studentResults = [];
        } elseif ($request->get('exams') && $request->exams !== '') {
            $subject_id = $request->input('subject');
            $exam_id = $request->input('exams');

            // Fetch the data
            $studentResults = DB::table('add_marks as am')
                ->join('class_manages as cm', 'am.class', 'cm.id')
                ->join('exams_subjects as es', 'am.subject_id', 'es.id')
                ->leftJoin('sections as s', 'am.section', 's.id')
                ->select(
                    'am.name',
                    'es.full_marks',
                    'am.marks_obtained',
                    'es.oral_marks',
                    'am.oral_marks_obtained',
                    'am.roll_no',
                    'cm.class',
                    's.section',
                    'es.pass_marks',
                    'es.subject'
                )
                ->where('am.exam_id', $exam_id)
                ->orderBy('am.roll_no', 'asc')  // Order by roll_no when fetching data
                ->get();

            // Organize the data
            $organizedData = [];
            foreach ($studentResults as $result) {
                $name = $result->name;
                $subjectData = [
                    'subject' => $result->subject,
                    'pass_marks' => $result->pass_marks,
                    'full_marks' => $result->full_marks,
                    'oral_marks' => $result->oral_marks,
                    'marks_obtained' => $result->marks_obtained,
                    'oral_marks_obtained' => $result->oral_marks_obtained,
                    'total_marks' => ($result->marks_obtained + $result->oral_marks_obtained),
                ];

                if (!array_key_exists($name, $organizedData)) {
                    $organizedData[$name] = [
                        'name' => strtoupper($name),
                        'class' => $result->class,
                        'section' => $result->section,
                        'roll_no' => $result->roll_no,
                        'subjects' => [$subjectData],
                    ];
                } else {
                    $organizedData[$name]['subjects'][] = $subjectData;
                }
            }

            // Convert the associative array into a simple array of values
            $studentResults = array_values($organizedData);

            // Sort studentResults by roll number
            usort($studentResults, function ($a, $b) {
                return $a['roll_no'] <=> $b['roll_no'];
            });

            // send blank results
            $studentExamSubject = [];
        } else {
            $studentResults = [];
            $studentExamSubject = [];
        }

        return view('manage_exam.result', compact('exams', 'subjects', 'studentResults', 'studentExamSubject'));
    }


    // Unit Test Result
    // Function to fetch student results and exam details
    private function fetchUnitTestResults($request)
    {
        // Current year session
        $session = DB::table('sessions')->where('active', '1')->first()->id;

        // Get Exams for the form
        $exams = DB::table('exams as ex')
            ->join('class_manages as c', 'c.id', 'ex.class')
            ->leftJoin('sections as s', 's.id', 'ex.section')
            ->where('ex.session_id', '=', $session)
            ->where('ex.status', '=', '1')
            ->where('ex.is_published', '=', '1')
            ->select('ex.id', 'ex.exam_name', 'c.class', 's.section')
            ->orderBy('ex.id', 'desc')
            ->get();

        // Get subjects for the selected exam
        $subjects = DB::table('exams_subjects as s')
            ->where('exam_id', '=', $request->exams)
            ->select('s.id', 's.subject')
            ->get();

        $studentResults = [];
        $selectedExam = null;

        // If exam is selected, fetch the results
        if ($request->get('exams') && $request->exams !== '') {
            $subject_id = $request->input('subject');
            $exam_id = $request->input('exams');

            // Fetch the selected exam details (exam name, class, and section)
            $selectedExam = DB::table('exams as ex')
                ->join('class_manages as c', 'c.id', 'ex.class')
                ->leftJoin('sections as s', 's.id', 'ex.section')
                ->where('ex.id', '=', $exam_id)
                ->select('ex.exam_name', 'c.class', 's.section')
                ->first();

            // Fetch the student results including subjectType and pass_marks
            $studentResults = DB::table('add_marks as am')
                ->join('class_manages as cm', 'am.class', 'cm.id')
                ->join('exams_subjects as es', 'am.subject_id', 'es.id')
                ->leftJoin('sections as s', 'am.section', 's.id')
                ->leftJoin('students as st', 'st.registration_id', 'am.registration_id')
                ->select(
                    'am.name',
                    'es.full_marks',
                    'am.marks_obtained',
                    'es.oral_marks',
                    'am.oral_marks_obtained',
                    'am.roll_no',
                    'cm.class',
                    's.section',
                    'es.pass_marks',
                    'es.subject',
                    'es.subjectType',
                    'am.registration_id' // Include registration_id for uniqueness
                )
                ->where('am.exam_id', $exam_id)
                ->where('st.session_id', $session)
                ->where('st.status', '=', '1')
                ->orderBy('am.roll_no', 'asc')
                ->get();

            // Organize the data
            $organizedData = [];
            foreach ($studentResults as $result) {
                $name = strtoupper($result->name); // Convert name to uppercase

                // Use registration_id as the unique key
                $registrationId = $result->registration_id;

                // Check if the student has already been processed
                if (!array_key_exists($registrationId, $organizedData)) {
                    $organizedData[$registrationId] = [
                        'name' => $name,
                        'class' => $result->class,
                        'section' => $result->section,
                        'roll_no' => $result->roll_no,
                        'total_marks' => 0,
                        'total_marks_obtained' => 0,
                        'total_pass_marks' => 0
                    ];
                }

                // Only add marks for the relevant subjectType
                if ($result->subjectType == 1) {
                    $organizedData[$registrationId]['total_marks'] += ($result->full_marks + $result->oral_marks);
                    $organizedData[$registrationId]['total_marks_obtained'] += ($result->marks_obtained+$result->oral_marks_obtained);
                    $organizedData[$registrationId]['total_pass_marks'] += $result->pass_marks;
                }
            }

            // Sort the students by total marks obtained
            usort($organizedData, function ($a, $b) {
                return $b['total_marks_obtained'] <=> $a['total_marks_obtained'];
            });

            $studentResults = array_values($organizedData);
        }

        return compact('exams', 'subjects', 'studentResults', 'selectedExam');
    }



    // View Unit Test Result
    public function UnitTestResult(Request $request)
    {
        // Fetch the data
        $data = $this->fetchUnitTestResults($request);

        // Return the view with the data
        return view('manage_exam.unit-test-result', $data);
    }

    // Download Result PDF
    public function downloadPDF(Request $request)
    {
        // Backend validation to check if an exam is selected
        $request->validate([
            'exams' => 'required'
        ], [
            'exams.required' => 'Please select an exam before downloading the PDF.'
        ]);

        // Fetch the selected exam details
        $exams = DB::table('exams')
            ->leftJoin('class_manages as cm', 'cm.id', '=', 'exams.class')
            ->where('exams.id', $request->input('exams'))
            ->select('exams.exam_name', 'cm.class')
            ->first();

        // If no exam is found, return with an error
        if (!$exams) {
            return redirect()->back()->with('error', 'Invalid exam selected.');
        }

        // Fetch the data
        $data = $this->fetchUnitTestResults($request);

        // Generate the PDF using the data
        $pdf = Pdf::loadView('manage_exam.unit-test-result-pdf', $data);

        // Create a custom filename
        $filename = $exams->exam_name . '-' . $exams->class . '.pdf';

        // Return the PDF download with the custom filename
        return $pdf->download($filename);
    }

    // Get all students for mark sheets
    public function mark_sheet(Request $request)
    {

        // Current year session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        // get Exams
        $exams = DB::table('exams as ex')
            ->join('class_manages as c', 'c.id', 'ex.class')
            ->leftJoin('sections as s', 's.id', 'ex.section')
            ->where('ex.session_id', '=', $session)
            ->where('ex.status', '=', '1')
            ->where('ex.is_published', '=', '1')
            ->select('ex.id', 'ex.exam_name', 'c.class', 's.section')
            ->orderBy('ex.id', 'desc')
            ->get();

        // Get class id ................
        if ($request->has('exams') && $request->exams !== '') {

            $exam_id = $request->exams;
            $exam_details = DB::table('exams as ex')
                ->where('id', $exam_id)
                ->select('class', 'section')
                ->first();

            if ($exam_details) {
                $class_id = $exam_details->class;

                $students = DB::table('students as st')
                    ->join('registrations as r', 'st.registration_id', 'r.id')
                    ->join('class_manages as c', 'st.class_id', 'c.id')
                    ->leftJoin('sections as s', 'st.section_id', 's.id')
                    ->select('r.id as registration_id', 'r.name', 'c.class', 's.section', 'r.fathersName', 'r.mobile')
                    ->where('st.class_id', $class_id)
                    ->where('st.status', '1')
                    ->get();
            } else {
                return redirect()->back()->withSuccess("Exam details not found for ID: $exam_id");
            }
        } else {
            $students = [];
        }

        return view('manage_exam.mark_sheet', compact('exams', 'students'));
    }

    // Final Marksheet
    public function final_mark_sheet(Request $request)
    {

        // Current year session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        // get Exams
        $exams = DB::table('exams as ex')
            ->join('class_manages as c', 'c.id', 'ex.class')
            ->leftJoin('sections as s', 's.id', 'ex.section')
            ->where('ex.session_id', '=', $session)
            ->where('ex.status', '=', '1')
            ->where('ex.is_published', '=', '1')
            ->select('ex.id', 'ex.exam_name', 'c.class', 's.section')
            ->orderBy('ex.id', 'desc')
            ->get();

        // Get class id ................
        if ($request->has('exams') && $request->exams !== '') {

            $exam_id = $request->exams;
            $exam_details = DB::table('exams as ex')
                ->where('id', $exam_id)
                ->select('class', 'section')
                ->first();

            if ($exam_details) {
                $class_id = $exam_details->class;

                $students = DB::table('students as st')
                    ->join('registrations as r', 'st.registration_id', 'r.id')
                    ->join('class_manages as c', 'st.class_id', 'c.id')
                    ->leftJoin('sections as s', 'st.section_id', 's.id')
                    ->select('r.id as registration_id', 'r.name', 'c.class', 's.section', 'r.fathersName', 'r.mobile')
                    ->where('st.class_id', $class_id)
                    ->where('st.status', '1')
                    ->get();
            } else {
                return redirect()->back()->withSuccess("Exam details not found for ID: $exam_id");
            }
        } else {
            $students = [];
        }

        return view('manage_exam.final_mark_sheet', compact('exams', 'students'));
    }

    // Published exams
    public function published()
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $examsData = DB::table('exams as ex')
            ->join('class_manages as mc', 'ex.class', 'mc.id')
            ->leftJoin('sections as s', 'ex.section', 's.section')
            ->select('ex.*', 'mc.class', 's.section')
            ->where('ex.session_id', '=', $session)
            ->where('ex.is_published', '=', '1')
            ->get();
        return view('manage_exam.published', compact('examsData'));
    }
}
