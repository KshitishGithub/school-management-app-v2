<?php

namespace App\Http\Controllers;

use App\Models\Committee;
use App\Models\setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class downloadPdfController extends Controller
{

    public function printRegistrationPdf($id)
    {
        try {
            $id = decrypt($id);
            // dd($decryptedId);
        } catch (DecryptException $e) {
            return redirect('404');
        }
        // Check id is exits or not
        if (!empty($id)) {
            $student = DB::table('registrations as r')
                ->where('r.id', $id)
                ->join('class_manages as c', 'r.class', '=', 'c.id')
                ->join('sessions as ss', 'ss.id', '=', 'r.session')
                ->leftJoin('sections as s', 'r.section', '=', 's.id')
                ->leftJoin('routes as ro', 'ro.id', '=', 'r.route')
                ->leftJoin('bus_stops as st', 'r.stops', '=', 'st.id')
                ->select(['r.*', 'c.class', 's.section', 'ss.session', 'st.bus_stops', 'ro.route'])
                ->first();
            $schoolDetails = setting::first();
            // return view('pdf.registration_pdf',compact('student','schoolDetails'));
            $pdf = Pdf::loadView('pdf.registration_pdf', compact('student', 'schoolDetails'));
            return $pdf->stream();
        }
    }


    // Download registration form.............
    public function downloadRegistrationPdf(Request $request, $id)
    {
        try {
            $id = decrypt($id);
            // dd($decryptedId);
        } catch (DecryptException $e) {
            return redirect('404');
        }
        // Check id is exits or not
        if (!empty($id)) {
            $student = DB::table('registrations as r')
                ->where('r.id', $id)
                ->join('class_manages as c', 'r.class', '=', 'c.id')
                ->join('sessions as ss', 'ss.id', '=', 'r.session')
                ->leftJoin('sections as s', 'r.section', '=', 's.id')
                ->leftJoin('routes as ro', 'ro.id', '=', 'r.route')
                ->leftJoin('bus_stops as st', 'r.stops', '=', 'st.id')
                ->select(['r.*', 'c.class', 's.section', 'ss.session', 'st.bus_stops', 'ro.route'])
                ->select(['r.*', 'c.class', 's.section', 'ss.session'])
                ->first();
            $schoolDetails = setting::first();
            // return view('pdf.registration_pdf',compact('student','schoolDetails'));
            $pdf = Pdf::loadView('pdf.registration_pdf', compact('student', 'schoolDetails'));
            return $pdf->download($student->name . '_registration_form' . '.pdf');
        }
    }

    // Print Fees
    public function print_fees($date, $id)
    {
        if (!empty($date) && !empty($id)) {
            try {
                $date = decrypt($date);
                $id = decrypt($id);
            } catch (DecryptException $e) {
                return redirect('404');
            }

            // Current session
            $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

            // Fetch and process the data
            $result = DB::table('fees as f')
                ->leftJoin('exams as e', 'e.id', 'f.exam_id')
                ->join('sessions as s', 'f.session_id', 's.id')
                ->join('students as st', 'f.s_id', 'st.registration_id')
                ->join('class_manages as cm', 'cm.id', 'st.class_id')
                ->leftJoin('sections as sc', 'st.section_id', 'sc.id')
                ->whereDate('f.created_at', '=', $date)
                ->where('st.session_id', '=', $session)
                ->where('f.s_id', '=', $id)
                ->select(
                    'cm.class as class',
                    'st.roll_no',
                    'sc.section',
                    'f.receiver',
                    's.session',
                    'f.name',
                    'f.remarks',
                    'f.created_at',
                    'f.fees_type',
                    'f.month',
                    'e.exam_name',
                    'f.amount'
                )
                ->get();

            if ($result) {
                // Process the grouped data
                $processedData = $result->map(function ($item) {
                    return [
                        'fees' => [
                            'fees_type' => $item->fees_type,
                            'month' => $item->month,
                            'exam_name' => $item->exam_name,
                            'amount' => $item->amount
                        ]
                    ];
                })->values();

                // return $processedData;

                // Prepare data for the view
                $data['session'] = $result->first()->session;
                $data['name'] = $result->first()->name;
                $data['class'] = $result->first()->class;
                $data['section'] = $result->first()->section;
                $data['roll_no'] = $result->first()->roll_no;
                $data['remarks'] = $result->first()->remarks;
                $data['receiver'] = $result->first()->receiver;
                $data['created_at'] = $result->first()->created_at;

                // return $data;
                $schoolDetails = setting::first();

                $qr = '';
                foreach ($processedData as $qrData) {
                    $qr .= '[ ' . $qrData['fees']['fees_type'] . '-';
                    if ($qrData['fees']['month']) {
                        $qr .= $qrData['fees']['month'] . '-';
                    } else {
                        $qr .= $qrData['fees']['exam_name'] . '-';
                    }
                    $qr .= $qrData['fees']['amount'] . ' ]';
                }

                // return $qr;
                // Generate the QR Code
                $qrData = QrCode::size(90)->generate($qr);

                // Total Amount
                $totalAmount = 0;
                foreach ($processedData as $amount) {
                    $totalAmount += $amount['fees']['amount'];
                }
                // return $totalAmount;
                // Return the view with data

                return view('pdf.fees', compact('data', 'processedData', 'schoolDetails', 'qrData', 'totalAmount'));

                // $pdf = Pdf::loadView('pdf.fees', compact('data', 'schoolDetails', 'qrData'));
                // return $pdf->stream();
            }
        }

        return redirect('404');
    }


    // Download the fees pdf file
    public function fees_download($id)
    {
        if (!empty($id)) {

            try {
                $id = decrypt($id);
                // dd($decryptedId);
            } catch (DecryptException $e) {
                return redirect('404');
            }

            $feesDetails = DB::table('fees as f')
                ->leftJoin('exams as e', 'e.id', 'f.exam_id')
                ->join('sessions as s', 'f.session_id', 's.id')
                ->where('f.id', '=', $id)
                ->select('e.exam_name', 'f.receiver', 's.session', 'f.name', 'f.fees_type', 'f.month', 'f.amount', 'f.remarks', 'f.created_at')
                ->first();
            $schoolDetails = setting::first();
            if ($feesDetails) {
                // Prepare data for the PDF
                $data['session'] = $feesDetails->session;
                $data['name'] = $feesDetails->name;
                $data['fees_type'] = $feesDetails->fees_type;
                $data['month'] = $feesDetails->month;
                $data['amount'] = $feesDetails->amount;
                $data['remarks'] = $feesDetails->remarks;
                $data['exam_name'] = $feesDetails->exam_name;
                $data['receiver'] = $feesDetails->receiver;
                $data['created_at'] = $feesDetails->created_at;

                // Create QR Code data string
                $qrDataString = $data['fees_type'] . ' - ';
                if ($data['month']) {
                    $qrDataString .= $data['month'];
                } else {
                    $qrDataString .= $data['exam_name'];
                }
                $qrDataString .= ' - ' . $data['amount'] . '.00/- - ' . \Carbon\Carbon::parse($data['created_at'])->format('d-m-Y , h:i A');

                // Generate the QR Code
                $qrData = QrCode::size(80)->generate($qrDataString);

                // Generate the PDF using a view named 'pdf.fees' and the prepared data
                $pdf = Pdf::loadView('pdf.fees', compact('data', 'schoolDetails', 'qrData'));

                // Alternatively, you can download the PDF file directly
                return $pdf->download($data['name'] . '_fees' . '.pdf');
            }
        }
    }


    // Marksheet printing functions
    public function printMarkSheet(Request $request, $exam_id, $registration_id)
    {
        $schoolDetails = setting::first();

        // Fetch the data
        $studentResults = DB::table('add_marks as am')
            ->join('registrations as r', 'am.registration_id', 'r.id')
            ->join('class_manages as cm', 'am.class', 'cm.id')
            ->join('exams as ex', 'ex.id', 'am.exam_id')
            ->join('exams_subjects as es', 'am.subject_id', 'es.id')
            ->join('sessions as ss', 'am.session', 'ss.id')
            ->leftJoin('sections as s', 'am.section', 's.id')
            ->select(
                'r.name',
                'es.full_marks',
                'es.oral_marks',
                'am.marks_obtained',
                'am.oral_marks_obtained',
                'am.roll_no',
                'am.registration_id as registration',
                'cm.class',
                'ex.exam_name as exam',
                's.section',
                'es.pass_marks',
                'es.subject',
                'es.subjectType', // Add subject_type to the select
                'ss.session',
                'r.photo',
                'r.fathersName'
            )
            ->where('am.exam_id', $exam_id)
            ->where('am.registration_id', $registration_id)
            ->orderBy('am.roll_no', 'asc')
            ->orderBy('es.id', 'asc')
            ->get();

        // Organize the data
        $organizedData = [];
        foreach ($studentResults as $result) {
            $name = $result->name;

            // Initialize totals and separate main/optional subjects
            $totalMarksObtained = 0;
            $totalFullMarks = 0;
            $mainSubjects = [];
            $optionalSubjects = [];

            foreach ($studentResults as $result) {
                $percentage = (($result->marks_obtained + $result->oral_marks_obtained) / ($result->full_marks + $result->oral_marks)) * 100;
                $total_marks_obtained = ($result->marks_obtained + $result->oral_marks_obtained);
                // Determine grade for each subject
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

                $subjectData = [
                    'subject' => $result->subject,
                    'pass_marks' => $result->pass_marks,
                    'full_marks' => $result->full_marks,
                    'oral_marks' => $result->oral_marks,
                    'total_marks' => ($result->oral_marks + $result->full_marks),
                    'marks_obtained' => $result->marks_obtained,
                    'oral_marks_obtained' => $result->oral_marks_obtained,
                    'total_marks_obtained' => $total_marks_obtained,
                    'grade' => $grade,
                    'subject_type' => $result->subjectType,
                ];

                // Check subject type and organize accordingly
                if ($result->subjectType == 1) {
                    // Main subject
                    $mainSubjects[] = $subjectData;

                    // Add to total marks
                    $totalMarksObtained += ($result->marks_obtained + $result->oral_marks_obtained);
                    $totalFullMarks += ($result->full_marks + $result->oral_marks);
                } else {
                    // Optional subject
                    $optionalSubjects[] = $subjectData;
                }
            }

            // Calculate overall percentage and grade (only for main subjects)
            $overallPercentage = ($totalMarksObtained / $totalFullMarks) * 100;

            $overallGrade = 'F'; // Default overall grade
            if ($overallPercentage >= 90) {
                $overallGrade = 'AA';
            } elseif ($overallPercentage >= 80) {
                $overallGrade = 'A+';
            } elseif ($overallPercentage >= 60) {
                $overallGrade = 'A';
            } elseif ($overallPercentage >= 45) {
                $overallGrade = 'B+';
            } elseif ($overallPercentage >= 35) {
                $overallGrade = 'B';
            } elseif ($overallPercentage >= 25) {
                $overallGrade = 'C';
            } else {
                $overallGrade = 'D';
            }

            // Combine main and optional subjects (main subjects first)
            $subjects = array_merge($mainSubjects, $optionalSubjects);

            // Organize student data
            $studentData = [
                'name' => $name,
                'fathersName' => $result->fathersName,
                'class' => $result->class,
                'section' => $result->section,
                'roll_no' => $result->roll_no,
                'photo' => $result->photo,
                'registration' => $result->registration,
                'exam' => $result->exam,
                'session' => $result->session,
                'subjects' => $subjects,
                'total_marks_obtained' => $totalMarksObtained,
                'overall_percentage' => number_format($overallPercentage, 2),
                'overall_grade' => $overallGrade,
            ];

            $organizedData[$name] = $studentData;
        }

        // Convert the associative array into a simple array of values
        $studentResults = array_values($organizedData);

        return view('pdf.marksheet', compact('schoolDetails', 'studentResults'));
        // $pdf = Pdf::loadView('pdf.marksheet', compact('schoolDetails', 'studentResults'));
        // return $pdf->stream();
    }

    // Marksheet printing functions
    public function finalMarkSheetPrint(Request $request, $registration_id)
    {
        $schoolDetails = setting::first();

        // Fetch the data
        return $studentResults = DB::table('add_marks as am')
            ->join('registrations as r', 'am.registration_id', 'r.id')
            ->join('class_manages as cm', 'am.class', 'cm.id')
            ->join('exams as ex', 'ex.id', 'am.exam_id')
            ->join('exams_subjects as es', 'am.subject_id', 'es.id')
            ->join('sessions as ss', 'am.session', 'ss.id')
            ->leftJoin('sections as s', 'am.section', 's.id')
            ->select(
                'am.name',
                'es.full_marks',
                'es.oral_marks',
                'am.marks_obtained',
                'am.oral_marks_obtained',
                'am.roll_no',
                'am.registration_id as registration',
                'cm.class',
                'ex.exam_name as exam',
                's.section',
                'es.pass_marks',
                'es.subject',
                'es.subjectType',
                'ss.session',
                'r.photo',
                'r.fathersName'
            )
            ->where('am.registration_id', $registration_id)
            ->where('ex.is_published', '=', '1')
            ->orderBy('am.roll_no', 'asc')
            ->orderBy('es.id', 'asc')
            ->get();

        $subjects = $studentResults->pluck('subject')->unique()->values()->all();

        return $subjectWiseMarks = $studentResults->groupBy('subject')->map(function ($subjectData) {
            return [
                'written_marks' => $subjectData->pluck('marks_obtained')->all(),
                'oral_marks' => $subjectData->pluck('oral_marks_obtained')->all(),
            ];
        })->toArray();



        return view('pdf.finalMarksheet', compact('schoolDetails', 'studentResults'));
        // $pdf = Pdf::loadView('pdf.marksheet', compact('schoolDetails', 'studentResults'));
        // return $pdf->stream();
    }


    // Download Marksheet function
    public function downloadMarkSheet(Request $request, $exam_id, $registration_id)
    {
        $schoolDetails = setting::first();

        // Fetch the data
        $studentResults = DB::table('add_marks as am')
            ->join('registrations as r', 'am.registration_id', 'r.id')
            ->join('class_manages as cm', 'am.class', 'cm.id')
            ->join('exams as ex', 'ex.id', 'am.exam_id')
            ->join('exams_subjects as es', 'am.subject_id', 'es.id')
            ->join('sessions as ss', 'am.session', 'ss.id')
            ->leftJoin('sections as s', 'am.section', 's.id')
            ->select(
                'am.name',
                'am.full_marks',
                'am.marks_obtained',
                'am.roll_no',
                'am.registration_id as registration',
                'cm.class',
                'ex.exam_name as exam',
                's.section',
                'es.pass_marks',
                'es.subject',
                'es.subjectType', // Fetch subject type (main/optional)
                'ss.session',
                'r.photo',
                'r.fathersName'
            )
            ->where('am.exam_id', $exam_id)
            ->where('am.registration_id', $registration_id)
            ->orderBy('am.roll_no', 'asc')
            ->orderBy('es.id', 'asc')
            ->get();

        // Organize the data
        $organizedData = [];
        foreach ($studentResults as $result) {
            $name = $result->name;

            // Initialize totals and separate main/optional subjects
            $totalMarksObtained = 0;
            $totalFullMarks = 0;
            $mainSubjects = [];
            $optionalSubjects = [];

            foreach ($studentResults as $result) {
                // Calculate percentage for each subject
                $percentage = ($result->marks_obtained / $result->full_marks) * 100;

                // Determine grade for each subject
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

                // Organize subject data
                $subjectData = [
                    'subject' => $result->subject,
                    'pass_marks' => $result->pass_marks,
                    'full_marks' => $result->full_marks,
                    'marks_obtained' => $result->marks_obtained,
                    'percentage' => number_format($percentage, 2),
                    'grade' => $grade,
                    'subject_type' => $result->subjectType,
                ];

                // Classify subjects as main or optional based on subjectType
                if ($result->subjectType == 1) {
                    // Main subject
                    $mainSubjects[] = $subjectData;

                    // Add to total marks
                    $totalMarksObtained += $result->marks_obtained;
                    $totalFullMarks += $result->full_marks;
                } else {
                    // Optional subject
                    $optionalSubjects[] = $subjectData;
                }
            }

            // Calculate overall percentage and grade (for main subjects only)
            $overallPercentage = ($totalMarksObtained / $totalFullMarks) * 100;

            // Assign the overall grade
            $overallGrade = 'F'; // Default
            if ($overallPercentage >= 90) {
                $overallGrade = 'AA';
            } elseif ($overallPercentage >= 80) {
                $overallGrade = 'A+';
            } elseif ($overallPercentage >= 60) {
                $overallGrade = 'A';
            } elseif ($overallPercentage >= 45) {
                $overallGrade = 'B+';
            } elseif ($overallPercentage >= 35) {
                $overallGrade = 'B';
            } elseif ($overallPercentage >= 25) {
                $overallGrade = 'C';
            } else {
                $overallGrade = 'D';
            }

            // Combine main and optional subjects
            $subjects = array_merge($mainSubjects, $optionalSubjects);

            // Organize student data
            $studentData = [
                'name' => $name,
                'fathersName' => $result->fathersName,
                'class' => $result->class,
                'section' => $result->section,
                'roll_no' => $result->roll_no,
                'photo' => $result->photo,
                'registration' => $result->registration,
                'exam' => $result->exam,
                'session' => $result->session,
                'subjects' => $subjects,
                'total_marks_obtained' => $totalMarksObtained,
                'overall_percentage' => number_format($overallPercentage, 2),
                'overall_grade' => $overallGrade,
            ];

            $organizedData[$name] = $studentData;
        }

        // Convert the associative array into a simple array of values
        $studentResults = array_values($organizedData);

        // Generate and return the PDF for download
        $pdf = Pdf::loadView('pdf.marksheet', compact('schoolDetails', 'studentResults'));
        return $pdf->download('marksheet_' . time() . '.pdf');
    }





    // Student ID Card Print functions
    public function printStudentIdCard($id)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        if (!empty($id)) {
            $student = DB::table('registrations as r')
                ->where('st.registration_id', $id)
                ->where('st.session_id', $session)
                ->join('students as st', 'st.registration_id', '=', 'r.id')
                ->join('class_manages as c', 'st.class_id', '=', 'c.id')
                ->join('sessions as ss', 'ss.id', '=', 'st.session_id')
                ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
                ->select([
                    'r.photo',
                    'r.mobile',
                    'r.name',
                    'r.fathersName',
                    'r.dateOfBirth',
                    'r.blood_group',
                    'r.village',
                    'r.postOffice',
                    'r.policeStation',
                    'r.district',
                    'r.pin',
                    'c.class',
                    'st.registration_id',
                    'st.session_id',
                    'st.class_id',
                    'st.section_id',
                    'st.roll_no',
                    's.section',
                    'ss.session'
                ])
                ->first();
            $committee = Committee::where('designation', 'principal')->first();
            $settings = setting::first();
            // encode the qr value
            $qrData = urlencode($student->registration_id . ',' . $student->session_id . ',' . $student->class_id . ',' . ($student->section_id ?? "NULL") . ',' . $student->roll_no);
            $qrData = QrCode::size(50)->generate($qrData);
            return view('pdf.student_id_card', compact('student', 'settings', 'committee', 'qrData'));
            // return view('pdf.student_id_card_2', compact('student','settings' , 'committee','qrData'));
            // $pdf = Pdf::loadView('pdf.student_id_card', compact('student','settings', 'committee','qrData'));
            // return $pdf->stream();
        }
    }

    // Student ID Card Download functions
    public function downloadStudentIdCard($id)
    {
        if (!empty($id)) {
            $student = DB::table('registrations as r')
                ->where('st.registration_id', $id)
                ->join('students as st', 'st.registration_id', '=', 'r.id')
                ->join('class_manages as c', 'st.class_id', '=', 'c.id')
                ->join('sessions as ss', 'ss.id', '=', 'st.session_id')
                ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
                ->select([
                    'r.photo',
                    'r.mobile',
                    'r.name',
                    'r.fathersName',
                    'r.dateOfBirth',
                    'r.blood_group',
                    'r.village',
                    'r.postOffice',
                    'r.policeStation',
                    'r.district',
                    'r.pin',
                    'c.class',
                    'st.registration_id',
                    'st.session_id',
                    'st.class_id',
                    'st.section_id',
                    'st.roll_no',
                    's.section',
                    'ss.session'
                ])
                ->first();
            $committee = Committee::where('designation', 'principal')->first();

            // encode the qr value
            $qrData = urlencode($student->registration_id . ',' . $student->session_id . ',' . $student->class_id . ',' . ($student->section_id ?? "NULL") . ',' . $student->roll_no);

            $settings = setting::first();

            // return view('pdf.student_id_card_2', compact('student','settings', 'committee','qrData'));
            $pdf = Pdf::loadView('pdf.student_id_card', compact('student', 'settings', 'committee', 'qrData'));
            return $pdf->download($student->name . '_ID' . '.pdf');
        }
    }

    //Print Blank Form
    public function printForm()
    {
        $schoolDetails = setting::first();
        // return view('pdf.blank_form', compact('schoolDetails'));
        $pdf = Pdf::loadView('pdf.blank_form', compact('schoolDetails'));
        return $pdf->stream();
    }


    // Download blank form.............
    public function downloadForm()
    {
        $schoolDetails = setting::first();
        $pdf = Pdf::loadView('pdf.blank_form', compact('schoolDetails'));
        return $pdf->download('blank_form' . '.pdf');
    }
}
