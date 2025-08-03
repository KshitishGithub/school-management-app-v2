<?php

namespace App\Http\Controllers;

use App\Models\class_manage;
use App\Models\fees;
use App\Models\session;
use App\Models\registration;
use App\Models\Student;
use App\Models\total_fees;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class FeesController extends Controller
{
    public function fees(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Current years classes
        $classes = class_manage::get();

        $students = DB::table('registrations as r')
            ->join('students as st', 'r.id', '=', 'st.registration_id')
            ->join('class_manages as c', 'st.class_id', '=', 'c.id')
            ->leftJoin('sections as s', 'st.section_id', '=', 's.id')
            ->where('st.session_id', '=', $session)
            ->where('r.status', '=', '2')
            ->where('st.status', '=', '1')
            ->select(['r.id', 'r.name', 'r.mobile', 'r.dateOfBirth', 'r.photo', 'c.class', 's.section', 'st.session_id', 'st.roll_no']);

        if ($request->class != null) {
            $students = $students->where('st.class_id', $request->class);
        } else {
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
        $students = $students->get();


        return view('fees.collect_fees', compact('students', 'classes'));
    }

    // Add Fees
    public function add($id)
    {
        try {
            $id = decrypt($id);
            // dd($decryptedId);
        } catch (DecryptException $e) {
            return redirect('404');
        }

        $studentData = DB::table('registrations as r')
            ->join('students as s', function ($join) {
                $join->on('r.id', '=', 's.registration_id')
                    ->on('r.session', '=', 's.session_id');
            })
            ->leftJoin('exams as e', 'r.class', 'e.class')
            ->select(
                'r.id',
                'r.name',
                'r.gander',
                'r.session',
                'r.mess',
                'r.transport',
                'r.hostel',
                's.created_at',
                's.class_id',
                'e.exam_name',
                'e.id as exam_id',
                DB::raw("MONTH(s.created_at) as month_number")
            )
            ->where('r.id', $id)
            ->first();

        $options = [];

        if ($studentData) {

            if ($studentData->transport == 'Yes') {
                $options[] = 'Transport Fees';
            }
            if ($studentData->hostel == 'Yes') {
                $options[] = 'Hostel Fees';
            }
            if ($studentData->mess == 'Yes') {
                $options[] = 'Mess Fees';
            }
        }



        $student = [
            'student_data' => $studentData,
            'options' => $options,
        ];

        return view('fees.add_fees', compact('student'));
    }


    // Store Fees.....
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            's_id' => 'required',
            'name' => 'required',
            'gender' => 'required',
            'fees_type' => 'required|array',
            'fees_type.*' => 'required|string',
            'amount' => 'required|array',
            'amount.*' => 'required|numeric',
            'status' => 'required|array',
            'status.*' => 'required|string',
            'month' => 'sometimes|array',
            'month.*' => 'nullable|string',
            'exam' => 'sometimes|array',
            'exam.*' => 'nullable',
            'remarks' => 'sometimes|array',
            'remarks.*' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        if ($request->s_id != null) {
            $notificationResponses = [];
            $feesRecords = [];

            // Get the active session
            $session = DB::table('sessions')->where('active', 1)->first()->id;

            DB::transaction(function () use ($request, $session, &$notificationResponses, &$feesRecords) {
                foreach ($request->fees_type as $index => $fees_type) {
                    // Search for existing fees
                    $feesQuery = Fees::where('s_id', $request->s_id)
                        ->where('fees_type', $fees_type)
                        ->where('session_id', $session);

                    if ($fees_type == 'Exam Fees') {
                        $feesQuery->where('exam_id', $request->exam[$index] ?? null);
                    } else {
                        $feesQuery->where('month', $request->month[$index] ?? null);
                    }

                    $fees = $feesQuery->first();

                    if ($fees) {
                        // Update fees status due or paid
                        $fees->status = $request->status[$index];
                        $fees->save();
                    }

                    // Add new fees record
                    $fees = new Fees;
                    $fees->s_id = $request->s_id;
                    $fees->name = $request->name;
                    $fees->session_id = $session;
                    $fees->gender = $request->gender;
                    $fees->fees_type = $fees_type;
                    $fees->exam_id = $fees_type == 'Exam Fees' ? $request->exam[$index] ?? null : null;
                    $fees->month = $fees_type != 'Exam Fees' ? $request->month[$index] ?? null : null;
                    $fees->amount = $request->amount[$index];
                    $fees->status = $request->status[$index];
                    $fees->receiver = Auth::user()->name;
                    $fees->remarks = $request->remarks[$index] ?? null;
                    $fees->save();

                    // Find or create total fees record
                    $totalFeesQuery = total_fees::where('s_id', $request->s_id)
                        ->where('fees_type', $fees_type)
                        ->where('session_id', $session);

                    if ($fees_type == 'Exam Fees') {
                        $totalFeesQuery->where('exam_id', $request->exam[$index] ?? null);
                    } else {
                        $totalFeesQuery->where('month', $request->month[$index] ?? null);
                    }

                    $total_fees = $totalFeesQuery->first();

                    if ($total_fees) {
                        // Update the total fees for the same month or same exam fees
                        $total_fees->amount += $request->amount[$index];
                        $total_fees->save();
                    } else {
                        // Add new total fees record
                        $total_fees = new total_fees();
                        $total_fees->s_id = $request->s_id;
                        $total_fees->fees_type = $fees_type;
                        $total_fees->session_id = $session;
                        $total_fees->exam_id = $fees_type == 'Exam Fees' ? $request->exam[$index] ?? null : null;
                        $total_fees->month = $fees_type != 'Exam Fees' ? $request->month[$index] ?? null : null;
                        $total_fees->amount = $request->amount[$index];
                        $total_fees->save();
                    }

                    $feesRecords[] = $fees;

                    // Send notification function
                    $notification = FirebasePushNotification(
                        [$request->s_id],
                        "Dear {$request->name}",
                        "Your {$fees_type} of {$request->amount[$index]} for the month of " . ($request->month[$index] ?? 'N/A') . " has been received successfully.",
                        "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcShVwg_8v81VD3V4qn89R49mbWM9vzyDodV6A&s"
                    );

                    $notificationResponses[] = json_decode($notification)->status;
                }
            });

            $allNotificationsSent = !in_array(false, $notificationResponses);

            return response()->json([
                'status' => true,
                'notification' => $allNotificationsSent,
                'url' => route('print.fees',  [
                    'date' => encrypt(date('Y-m-d')),
                    'id' => encrypt($request->s_id)
                ]),
                'message' => 'Fees added successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Student ID not found.',
            ]);
        }
    }



    // Fees Details ......
    public function details($id)
    {
        if (!empty($id)) {
            try {
                $id = decrypt($id);
            } catch (DecryptException $e) {
                return redirect('404');
            }

            // Current session
            $session = DB::table('sessions')->where('active', 1)->first()->id;

            // Retrieve fees data with related exam data
            $fees = DB::table('fees as f')
                ->leftJoin('exams as e', 'e.id', '=', 'f.exam_id')
                ->where('f.s_id', '=', $id)
                ->where('f.session_id', '=', $session)
                ->select('f.*', 'e.exam_name')
                ->orderBy('f.created_at', 'desc')
                ->get();

            // if fees is not avaiable
            if ($fees->isEmpty()) {
                return redirect()->route('fees.list')->withError('Fees not avaiable');
            }

            // Add combined fees_type and exam_name
            $fees = $fees->map(function ($item) {
                $item->combined_fees_type = $item->fees_type . ($item->exam_name ? ' - ' . $item->exam_name : '');
                return $item;
            });

            // Group fees by date
            $Fees = $fees->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d');
            });

            // Process the grouped fees to include combined_fees_type and total amount
            $Fees = $Fees->map(function ($group) {
                $combined_fees_types = [];
                $total_amount = 0;

                foreach ($group as $fee) {
                    $combined_fees_types[] = $fee->combined_fees_type;
                    $total_amount += $fee->amount;
                }

                return [
                    // 'fees' => $group,
                    'combined_fees_types' => array_unique($combined_fees_types),
                    'total_amount' => $total_amount,
                ];
            });

            if ($Fees->isNotEmpty()) {
                return view('fees.fees_details', compact('Fees','id'));
            } else {
                return redirect('404');
            }
        }
    }

    private function organizeFeesData($feesData)
    {
        $feesByType = [
            'Exam Fees' => [],
        ];

        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        foreach ($feesData as $fee) {
            $feesType = $fee->fees_type;

            if ($feesType === 'Exam Fees') {
                $examName = $fee->exam_name ?: 'No Exam';
                $feesByType['Exam Fees'][] = [
                    'amount' => $fee->amount,
                    'examName' => $examName,
                ];
            } else {
                $month = $fee->month ?: 'No Month';
                if (!isset($feesByType[$feesType])) {
                    $feesByType[$feesType] = [];
                }
                $feesByType[$feesType][$month] = (int)$fee->amount;
            }
        }

        // Fill missing months with 0 for each non-Exam Fee type
        foreach ($feesByType as $key => &$fees) {
            if ($key !== 'Exam Fees') {
                $fees = array_combine($months, array_replace(array_fill_keys($months, 0), $fees));
            }
        }

        return $feesByType;
    }


    // Status ..............
    public function status($id)
    {
        try {
            $id = decrypt($id);
            // dd($decryptedId);
        } catch (DecryptException $e) {
            return redirect('404');
        }

        // Retrieve active session ID
        $session = DB::table('sessions')->where('active', '1')->value('id');

        // Retrieve fees data based on conditions
        $feesData = DB::table('total_fees as f')
            ->leftJoin('exams as e', 'e.id', 'f.exam_id')
            ->where('f.s_id', '=', $id)
            ->where('f.session_id', '=', $session)
            ->select('f.*', 'e.exam_name')
            ->orderBy('f.id', 'desc')
            ->get();


        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        // Organize data into the desired structure
        $feesByType = $this->organizeFeesData($feesData);
        return view('fees.fees_status', compact('feesByType', 'months'));
    }



    // Fees Collection Status........
    public function collection_status(Request $request)
    {

        // Current session
        $session = DB::table('sessions')->where('active', '1')->value('id');

        $feesQuery = DB::table('fees as f')
            ->leftJoin('exams as e', 'e.id', '=', 'f.exam_id')
            ->where('f.session_id', '=', $session)
            ->select('f.*', 'e.exam_name')
            ->orderBy('f.id', 'desc');

        if ($request->start_date !== '' && $request->end_date !== '') {
            try {
                $start_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d');
                $end_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d');

                $feesQuery->whereBetween(DB::raw('DATE(f.created_at)'), [$start_date, $end_date]);
                $feesCollectionStatus = $feesQuery->get();
                $totalAmount = $feesCollectionStatus->sum('amount');
            } catch (\Exception $e) {
                // Handle date format exception, for example:
                // Log the error or set default dates
                $start_date = null;
                $end_date = null;
                $feesCollectionStatus = [];
                $totalAmount = '00';
            }
        }

        return view('fees.collection_status', compact('feesCollectionStatus', 'totalAmount'));
    }


    // Due students Fees
    public function due(Request $request)
    {
        $session = DB::table('sessions')->where('active', '1')->first()->id;

        $classes = class_manage::get();

        $fees_amount = DB::table('set_prices')
            ->where('price_type', $request->fees_type)
            ->where('class_id', $request->class)
            ->select('price_type', 'prices')
            ->first();

        $total_fee = $fees_amount ? (int) $fees_amount->prices : 0;

        $students = DB::table('students as s')
            ->join('registrations as r', 'r.id', 's.registration_id')
            ->join('class_manages as cm', 'cm.id', '=', 's.class_id')
            ->where('s.session_id', '=', $session)
            ->where('s.class_id', '=', $request->class)
            ->where('s.status', '=', '1')
            ->select(
                'r.id',
                'r.name',
                'r.session',
                'r.mess',
                'r.transport',
                'r.hostel',
                's.created_at',
                's.class_id',
                's.roll_no',
                's.session_id',
                'cm.class'
            );

        if ($request->fees_type == 'Hostel Fees') {
            $students = $students->where('r.hostel', '=', 'Yes');
        } elseif ($request->fees_type == 'Mess Fees') {
            $students = $students->where('r.mess', '=', 'Yes');
        }

        if ($request->month) {
            if (!preg_match('/\d{4}-\d{2}/', $request->month)) {
                $requestMonth = Carbon::now()->year . '-' . Carbon::parse($request->month)->format('m');
            } else {
                $requestMonth = $request->month;
            }

            $requestMonthStart = Carbon::createFromFormat('Y-m', $requestMonth)->startOfMonth();

            $students = $students->where('s.created_at', '<=', $requestMonthStart);
        }

        $students = $students->get();

        // Get all fees data
        $feesData = DB::table('fees as f')
            ->where('fees_type', '=', $request->fees_type)
            ->where('month', '=', $request->month)
            ->where('session_id', '=', $session)
            ->select(
                'f.amount',
                'f.s_id',
                'f.status',
                'f.month',
                'f.fees_type'
            )
            ->get();

        // Group and sum amounts for same student, month, and fees_type
        $feesMap = $feesData->groupBy('s_id')->map(function ($entries) {
            $totalAmount = $entries->sum(function ($entry) {
                return (int) $entry->amount;
            });

            $first = $entries->first();
            return (object)[
                'amount' => $totalAmount,
                's_id' => $first->s_id,
                'status' => $totalAmount > 0 ? 'Paid' : 'Due',
                'month' => $first->month,
                'fees_type' => $first->fees_type,
            ];
        });

        // Attach fees info to students
        $students = $students->map(function ($student) use ($total_fee, $feesMap) {
            $feeEntry = $feesMap[$student->id] ?? null;
            $totalPaid = $feeEntry ? (int) $feeEntry->amount : 0;

            $dueAmount = max(0, $total_fee - $totalPaid);

            $student->amount = $totalPaid;

            // Admin manually marked Paid for partial payment
            if ($feeEntry && $feeEntry->status == 'Paid') {
                $student->status_label = 'Paid';
            } elseif ($totalPaid == 0) {
                $student->status_label = 'Due';
                $student->due_amount = $total_fee;
            } else {
                $student->status_label = 'Due';
                $student->due_amount = $total_fee - $totalPaid;
            }


            return $student;
        });

        if ($request->status == 'Paid') {
            $students = $students->where('status_label', 'Paid');
        } else {
            $students = $students->whereIn('status_label', ['Due']);
        }

        $endMonth = Carbon::now()->month;
        $monthsList = [];

        for ($i = 1; $i <= $endMonth; $i++) {
            $monthsList[] = Carbon::createFromDate(null, $i, 1)->format('F');
        }

        $selectedMonth = $request->month ?? Carbon::now()->format('F');

        return view('fees.due_fees', compact('classes', 'students', 'monthsList', 'selectedMonth'));
    }






    // Paid Fees
    public function paid(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Current years session
        $classes = class_manage::where('session_id', $session)->get();

        // Search paid students
        $students = DB::table('registrations as r')
            ->join('class_manages as c', 'r.class', '=', 'c.id')
            ->join('students as st', 'r.id', '=', 'st.registration_id')
            ->join('fees as f', 'st.id', '=', 'f.s_id')
            ->leftJoin('sections as s', 'r.section', '=', 's.id');
        if ($request->class != null) {
            $students = $students->where('st.class_id', $request->class);
        }
        if ($request->month != null) {
            $students = $students->where('f.month', $request->month);
        } else {
            $students = $students->where('f.month', Carbon::now()->monthName);
        }
        $students = $students->where('f.fees_type', 'Monthly Fees')
            ->select(['r.name', 'r.photo', 'r.created_at', 'r.mobile', 'st.registration_id as id', 'c.class', 's.section', 'st.roll_no', 'f.fees_type', 'f.month', 'f.fees_type', 'f.amount', 'f.updated_at as paid_date'])
            ->get();
        $month = $request->month ?? Carbon::now()->monthName;
        return view('fees.paid_fees', compact('classes', 'students', 'month'));
    }
}
