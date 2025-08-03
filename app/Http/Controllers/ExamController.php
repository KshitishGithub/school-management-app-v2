<?php

namespace App\Http\Controllers;

use App\Models\addMark;
use App\Models\class_manage;
use App\Models\exam;
use App\Models\exams_subject;
use App\Models\section;
use App\Models\setting;
use App\Models\subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    public function index()
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $examsData = DB::table('exams as ex')
            ->join('class_manages as mc', 'ex.class', 'mc.id')
            ->leftJoin('sections as s', 'ex.section', 's.section')
            ->select('ex.*', 'mc.class', 's.section')
            ->where('ex.session_id', '=', $session)
            ->where('ex.is_published', '=', '0')
            ->get();
        return view('exam.list_exam', compact('examsData'));
    }

    public function add()
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Current years session
        $classes = class_manage::where('session_id', $session)->get();
        return view('exam.add_exam', compact('classes'));
    }


    // Get the section and subject name at the same time
    public function getSectionandSubject(Request $request)
    {
        if ($request != null) {
            $sections = section::where('class_manages_id', $request->class_id)
                ->select('id', 'section')
                ->get();
            if ($sections->count() == 0) {
                $sectionsData = [
                    'status' => false,
                    'sections' => $sections
                ];
            } else {
                $sectionsData = [
                    'status' => true,
                    'sections' => $sections
                ];
            }

            // Get all subject
            $subject = subject::where('class_manages_id', $request->class_id)
                ->select('id', 'subject')
                ->get();
            if ($subject->count() == 0) {
                $subjectData = [
                    'status' => false,
                    'subject' => $subject
                ];
            } else {
                $subjectData = [
                    'status' => true,
                    'subject' => $subject
                ];
            }

            return response()->json([$sectionsData, $subjectData]);
        }
    }


    // Exam Details.............
    public function exam_details(Request $request)
    {
        // Current sessions ........
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $examsDetails = DB::table('exams as ex')
            ->join('exams_subjects as es', 'es.exam_id', 'ex.id')
            ->where('ex.session_id', '=', $session)
            ->where('ex.id', '=', $request->id)
            ->select('es.*')
            ->get();

        if ($examsDetails->isNotEmpty()) {
            $data = "";
            foreach ($examsDetails as $i => $examsDetail) {
                $data .= "<tr>
                <td>" . ($i + 1) . "</td>
                <td>" . $examsDetail->subject . "</td>
                <td>" . \Carbon\Carbon::parse($examsDetail->exam_date)->format('d-M-Y') . "</td>
                <td>" . $examsDetail->exam_day . "</td>
                <td>" . \Carbon\Carbon::parse($examsDetail->start_time)->format('h:i:s A') . "</td>
                <td>" . \Carbon\Carbon::parse($examsDetail->end_time)->format('h:i:s A') . "</td>
                <td>" . $examsDetail->full_marks . "</td>
                <td>" . $examsDetail->oral_marks . "</td>
                <td>" . $examsDetail->pass_marks . "</td>
                <td>" . ($examsDetail->subjectType == '1' ? "<span class='span span-info'>Compulsory</span>" : "<span class='span span-secondary'>Optional</span>") . "</td>
            </tr>";
            }
            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => "",
            ]);
        }
    }


    // Store exams data........
    public function store(Request $request)
    {
        // Current year session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $validator = Validator::make($request->all(), [
            'exam_name' => 'required',
            'class' => 'required',
            'subject' => 'required',
            'exam_date' => 'required',
            'exam_day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'full_marks' => 'required',
            'oral_marks' => 'required',
            'pass_marks' => 'required',
            'subject_type' => 'required',
        ]);

        if ($validator->passes()) {
            $exam = new Exam;
            $exam->session_id = $session;
            $exam->exam_name = $request->exam_name;
            $exam->class = $request->class;
            $exam->section = $request->section;
            $exam->fees = $request->fees;
            $exam->save();

            // Assuming $request->subject is an array of subjects
            for ($i = 1; $i <  count($request->subject); $i++) {
                // Convert "Compulsory" to 1 and "Optional" to 0
                $subjectType = strtolower($request->subject_type[$i]) === 'compulsory' ? 1 : 0;

                $exam_subject = new exams_subject;
                $exam_subject->exam_id = $exam->id;
                $exam_subject->subject = $request->subject[$i];
                $exam_subject->exam_date = $request->exam_date[$i];
                $exam_subject->exam_day = $request->exam_day[$i];
                $exam_subject->start_time = $request->start_time[$i];
                $exam_subject->end_time = $request->end_time[$i];
                $exam_subject->full_marks = $request->full_marks[$i];
                $exam_subject->oral_marks = $request->oral_marks[$i];
                $exam_subject->pass_marks = $request->pass_marks[$i];
                $exam_subject->subjectType = $subjectType;  // Save as 1 or 0
                $exam_subject->save();
            }

            session()->flash('success', 'Exam added successfully.');
            return response()->json([
                'status' => true,
                'message' => "Exam added successfully."
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Exam not added."
            ]);
        }
    }

    // Edit Exams
    public function editExams($id)
    {
        // Current sessions ........
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        // Classes
        $classes = class_manage::where('session_id', $session)->get();

        $exams = DB::table('exams as ex')
            ->where('ex.session_id', '=', $session)
            ->where('ex.id', '=', $id)
            ->select('ex.*')
            ->first();
        $examsSubject = DB::table('exams as ex')
            ->join('exams_subjects as es', 'es.exam_id', 'ex.id')
            ->where('ex.session_id', '=', $session)
            ->where('ex.id', '=', $id)
            ->select('es.*')
            ->get();

        $subjects = DB::table('subjects as s')->where('class_manages_id', '=', $exams->class)->get('subject');

        return view("exam.edit_exam", compact('exams', 'examsSubject', 'classes', 'subjects'));
    }

    // Update Exams
    public function update(Request $request, $id)
    {
        // Start the database transaction
        DB::beginTransaction();

        try {
            // Current year session
            $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
            
            // Validate the inputs
            $validator = Validator::make($request->all(), [
                'exam_name' => 'required',
                'class' => 'required',
                'subject' => 'required|array',
                'exam_date' => 'required|array',
                'exam_day' => 'required|array',
                'start_time' => 'required|array',
                'end_time' => 'required|array',
                'full_marks' => 'required|array',
                'oral_marks' => 'required|array',
                'pass_marks' => 'required|array',
                'subject_type' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => "Exam update failed. Validation error.",
                    'errors' => $validator->errors()
                ]);
            }

            // Find and update the exam record
            $exam = Exam::findOrFail($id);
            $exam->session_id = $session;
            $exam->exam_name = $request->exam_name;
            $exam->class = $request->class;
            $exam->section = $request->section;
            $exam->fees = $request->fees ?? '0';
            $exam->save();

            // Store the subjects that are coming in the request
            $requestSubjects = $request->subject;

            // Get all subjects that are currently in the database for this exam
            $existingSubjects = exams_subject::where('exam_id', $id)->pluck('subject')->toArray();

            // Loop through each subject from the request to update or insert
            for ($i = 1; $i < count($requestSubjects); $i++) {
                $subjectType = strtolower($request->subject_type[$i]);
                $subject = $requestSubjects[$i];
                // Check if the subject already exists in the database
                $exam_subject = exams_subject::where('exam_id', $id)
                    ->where('subject', $subject)
                    ->first();

                if ($exam_subject) {
                    // Update existing subject
                    $exam_subject->subject = $subject;
                    $exam_subject->exam_date = $request->exam_date[$i];
                    $exam_subject->exam_day = $request->exam_day[$i];
                    $exam_subject->start_time = $request->start_time[$i];
                    $exam_subject->end_time = $request->end_time[$i];
                    $exam_subject->full_marks = $request->full_marks[$i];
                    $exam_subject->oral_marks = $request->oral_marks[$i];
                    $exam_subject->pass_marks = $request->pass_marks[$i];
                    $exam_subject->subjectType = $subjectType;
                    $exam_subject->save();
                } else {
                    // Insert new subject
                    $new_exam_subject = new exams_subject;
                    $new_exam_subject->exam_id = $id;
                    $new_exam_subject->subject = $subject;
                    $new_exam_subject->exam_date = $request->exam_date[$i];
                    $new_exam_subject->exam_day = $request->exam_day[$i];
                    $new_exam_subject->start_time = $request->start_time[$i];
                    $new_exam_subject->end_time = $request->end_time[$i];
                    $new_exam_subject->full_marks = $request->full_marks[$i];
                    $new_exam_subject->oral_marks = $request->oral_marks[$i];
                    $new_exam_subject->pass_marks = $request->pass_marks[$i];
                    $new_exam_subject->subjectType = $subjectType;
                    $new_exam_subject->save();
                }
            }

            // Delete subjects that exist in the database but not in the request
            $subjectsToDelete = array_diff($existingSubjects, $requestSubjects);
            if (!empty($subjectsToDelete)) {
                exams_subject::where('exam_id', $id)
                    ->whereIn('subject', $subjectsToDelete)
                    ->delete();
            }

            // Commit the transaction
            DB::commit();

            // Return a successful response
            session()->flash('success', 'Exam updated successfully.');
            return response()->json([
                'status' => true,
                'error' => false,
                'message' => "Exam updated successfully."
            ]);
        } catch (\Exception $e) {

            Log::error('Exam update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Rollback the transaction on any error
            DB::rollback();

            // Return a failure response with the error message
            return response()->json([
                'status' => true,
                'message' => 'Exam update failed. Something went wrong.',
                'error' => true,
            ]);
        }
    }



    // Delete Exam .................
    public function exam_delete(Request $request)
    {
        $exams = exam::find($request->id);
        if (!empty($exams)) {
            $exams->delete();
            exams_subject::where('exam_id', '=', $request->id)->delete();
            addMark::where('exam_id', '=', $request->id)->delete();
            return response()->json([
                'status' => true,
                'message' => "Exam and associated subjects and number deleted successfully."
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Exam not found."
            ]);
        }
    }
    // Publish Exam .................
    public function exam_publish(Request $request)
    {
        $exams = Exam::join('class_manages as c', 'exams.class', '=', 'c.id')
            ->where('exams.id', $request->id)
            ->select('c.class as class_name', 'exams.*')
            ->first();

        if (!empty($exams)) {
            $exams->is_published = '1';
            $exams->save();

            // ! Send notification using onesignal notification --------------------------------
            $bigPicture = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ2sWjLPQLz6gtn7kSGftcvh5Qrr85K1LJDOA&s';
            $settings = setting::all()->first();
            $largeIcon = url('storage/images/setting/' . $settings->logo);
            $notification = OneSignalPushNotification("🔉🔉🔉 Result Published", "👨‍🎓 The results of the $exams->exam_name of class $exams->class_name have been published.", $bigPicture, $largeIcon);


            if (json_decode($notification)->status == true) {
                session()->flash('success', 'Exam published successfully.');
                return response()->json([
                    'status' => true,
                    'notification' => true,
                    'message' => "Exam published successfully.",
                ]);
            } else {
                session()->flash('success', 'Exam published successfully.');
                return response()->json([
                    'status' => true,
                    'notification' => false,
                    'message' => "Exam published successfully.",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => "Exam not found."
            ]);
        }
    }

    public function exam_unpublish(Request $request)
    {
        $exams = Exam::join('class_manages as c', 'exams.class', '=', 'c.id')
            ->where('exams.id', $request->id)
            ->select('c.class as class_name', 'exams.*')
            ->first();

        if (!empty($exams)) {
            $exams->is_published = '0';
            $exams->save();


            session()->flash('success', 'Exam unpublished successfully.');
            return response()->json([
                'status' => true,
                'notification' => false,
                'message' => "Exam unpublished successfully.",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Exam not found."
            ]);
        }
    }
}
