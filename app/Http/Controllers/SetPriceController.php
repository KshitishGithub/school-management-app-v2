<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SetPriceController extends Controller
{
    // index
    public function index()
    {
        // Current year session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $classes = DB::table('class_manages')->get(['id', 'class']);

        $prices = DB::table('set_prices as s')
            ->join('class_manages as c', 'c.id', '=', 's.class_id')
            ->get(['s.id','s.price_type', 's.prices', 'c.class']);

        return view('set_price.price', compact('classes', 'prices'));
    }

    // Store the price
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make(
            $request->all(),
            [
                'class_id' => 'required|numeric|exists:class_manages,id', // Ensure the class_id exists in class_manages table
                'prices' => 'required|numeric|min:0', // Price should be a positive number
                'price_type' => 'required',

            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 422); // Unprocessable Entity status code
        }

        try {
            // Insert the validated data into the database
            DB::table('set_prices')->insert([
                'class_id' => $request->input('class_id'),
                'price_type' => $request->input('price_type'),
                'prices' => $request->input('prices'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Set a success message in the session
            session()->flash('success', 'Prices added successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Prices added successfully.',
            ], 201); // Created status code
        } catch (\Exception $e) {
            // Handle any database errors
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while adding the price.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error status code
        }
    }

    // destroy the price
    public function destroy($id){
        try {
            // Delete the price from the database
            DB::table('set_prices')->where('id', $id)->delete();

            // Set a success message in the session
            session()->flash('success', 'Price deleted successfully.');

            return response()->json([
                'status' => true,
               'message' => 'Price deleted successfully.',
            ], 200); // OK status code
        } catch (\Exception $e) {
            // Handle any database errors
            return response()->json([
                'status' => false,
               'message' => 'An error occurred while deleting the price.',
                'error' => $e->getMessage(),
            ], 500); // Internal Server Error status code
        }
    }

    // get Price
    public function getPrice(Request $request)
    {
        $feesType = $request->input('feesType');
        $classId = $request->input('class_id');
        $studentId = $request->input('s_id');
        $month = $request->input('month');

        // Get the current active session
        $session = DB::table('sessions')->where('active', '1')->value('id');

        if (!$session) {
            return response()->json([
                'status' => false,
                'message' => 'Active session not found.'
            ], 404);
        }

        // Check if there are due fees for the current session and month
        $dueAmount = DB::table('fees')
            ->where('fees_type', $feesType)
            ->where('s_id', $studentId)
            ->where('session_id', $session)
            ->where('month', $month)
            ->where('status', 'Due')
            ->value('amount'); // Use value() to directly retrieve a single column value

        // Get the base price for the fees type and class
        $price = DB::table('set_prices')
            ->where('class_id', $classId)
            ->where('price_type', $feesType)
            ->value('prices'); // Use value() to directly retrieve the price

        if (!$price) {
            return response()->json([
                'status' => false,
                'message' => 'Price not found for the given class and fees type.'
            ]);
        }

        // Calculate the remaining price if there's a due amount
        if ($dueAmount) {
            $price -= $dueAmount; // Deduct the due amount from the base price
        }

        return response()->json([
            'status' => true,
            'price' => $price
        ]);
    }


    // get Month and exams
    public function getMonth(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $id = $request->input('s_id');
        $fees_type = $request->input('feesType');
        $class_id = $request->input('class_id');

        // if the fees type is exam fees
        if($fees_type == 'Exam Fees'){
            // get all the exams for the class in the current session
            $exams = DB::table('exams')
                ->where('class','=',$class_id)
                ->where('session_id','=',$session)
                ->where('fees','>', '0')
                ->get('id');


            // get all the paid exmas
            $paidExams = DB::table('fees')
                ->where('fees_type','=','Exam Fees')
                ->where('session_id','=',$session)
                ->where('status','=','Paid')
                ->where('s_id','=',$id)
                ->get('exam_id');

            // due exams
            $dueExams = array_diff($exams->pluck('id')->toArray(), $paidExams->pluck('exam_id')->toArray());

            // get the exams data
            $exams = DB::table('exams')
                ->whereIn('id', $dueExams)
                ->select('id','exam_name')
                ->get();


            return response()->json([
               'status' => true,
               'type' => 'exam',
               'exams' => $exams
            ]);
        }

        $studentData = DB::table('registrations as r')
            ->join('students as s', function ($join) {
                $join->on('r.id', '=', 's.registration_id');
                    // ->on('r.session', '=', 's.session_id');
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
                's.session_id',
                'e.exam_name',
                'e.id as exam_id',
                DB::raw("MONTH(s.created_at) as month_number")
            )
            ->where('r.id', $id)
            ->where('s.session_id', '=', $session)
            ->first();


        $months = [];

        $startMonthNumber = Carbon::parse($studentData->created_at)->month;

        for ($i = $startMonthNumber; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->monthName;
        }


        // Remove the month from the month list which month is already paid.......
        $paidMonths = DB::table('fees')
            ->where('s_id', $id)
            ->where('session_id', '=', $session)
            ->where('status', '=', "Paid")
            ->where('fees_type', '=', $fees_type)
            ->pluck('month')
            ->toArray();

        $months = array_diff($months, $paidMonths);

        return response()->json(array(
            'status' => true,
            'type' => 'month',
            'months' => $months
        ));
    }

    // get Exam Price
    public function getExamPrice(Request $request)
    {
        $classId = $request->input('class_id');
        $studentId = $request->input('s_id');
        $exam_id = $request->input('exam_id');
        $feesType  = $request->input('feesType');

        // Get the current active session
        $session = DB::table('sessions')->where('active', '1')->value('id');

        if (!$session) {
            return response()->json([
                'status' => false,
                'message' => 'Active session not found.'
            ], 404);
        }

        // Check if there are due fees for the current session and month
        $examFees = DB::table('exams')
            ->where('class','=', $classId)
            ->where('session_id','=',$session)
            ->where('id','=', $exam_id)
            ->where('status','=', '1')
            ->value('fees');

        // Check if there are due fees for the current session and month
        $dueAmount = DB::table('fees')
            ->where('fees_type', $feesType)
            ->where('exam_id','=', $exam_id)
            ->where('s_id', $studentId)
            ->where('session_id', $session)
            ->where('status', 'Due')
            ->value('amount');

        if (!$examFees) {
            return response()->json([
                'status' => false,
                'message' => 'Price not found for the given class and fees type.'
            ], 404);
        }

        // Calculate the remaining price if there's a due amount
        if ($dueAmount) {
            $examFees -= $dueAmount; // Deduct the due amount from the base price
        }

        return response()->json([
            'status' => true,
            'examFees' => $examFees
        ]);
    }
}
