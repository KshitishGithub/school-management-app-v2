<?php

namespace App\Http\Controllers;

use App\Models\book_type;
use App\Models\class_manage;
use App\Models\Library;
use App\Models\library_sale;
use App\Models\subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $libraries = Library::orderBy('libraries.id', 'desc')
            ->leftJoin('class_manages as cm', 'cm.id', '=', 'libraries.class')
            ->leftJoin('subjects', 'subjects.id', '=', 'libraries.subject')
            ->select('libraries.*', 'subjects.subject as subject_name', 'cm.class')
            ->where('libraries.quantity', '>', 0)
            // ->where('libraries.status', '=', 1)
            ->paginate(10);

        return view('library.index', compact('libraries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $book_type = book_type::get();
        $classes = DB::table('class_manages')->get();
        return view('library.add-books', compact('book_type', 'classes'));
    }

    public function create_type()
    {
        $book_type = book_type::get();
        return view('library.add-type', compact('book_type'));
    }

    // Get subjects
    public function getSubject(Request $request)
    {
        if ($request != null) {

            // Get all subject
            $subject = subject::where('class_manages_id', $request->class_id)
                ->select('id', 'subject')
                ->get();
            if ($subject->count() == 0) {
                $subjectData = [
                    'status' => false,
                    'subjects' => $subject
                ];
            } else {
                $subjectData = [
                    'status' => true,
                    'subjects' => $subject
                ];
            }

            return response()->json($subjectData);
        }
    }


    // Store books type

    public function store_type(Request $request)
    {
        // store book type
        $validator = Validator::make($request->all(), [
            'book_type' => 'required',
        ]);

        if ($validator->passes()) {
            $book_type = new book_type;
            $book_type->type = $request->input('book_type');

            $book_type->save();
            session()->flash('success', 'Book Type added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Book Type added successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => $validator,
            ]);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate the request
        $validator = Validator::make($request->all(), [
            'book_name' => 'required|string|max:255',
            'class' => 'required|integer',
            'subject' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'type' => 'required',
            'status' => 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        // store book
        $library = new Library;
        $library->book_name = $request->input('book_name');
        $library->class = $request->input('class');
        $library->subject = $request->input('subject');
        $library->quantity = $request->input('quantity');
        $library->price = $request->input('price');
        $library->type = $request->input('type');
        $library->status = $request->input('status');

        $library->save();
        // session()->flash('success', 'Book added successfully');
        return response()->json([
            'status' => true,
            'message' => 'Book added successfully',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $book_type = book_type::get();
        $classes = DB::table('class_manages')->where('session_id', '=', $session)->get();
        // edit the library
        $library = Library::orderBy('libraries.id', 'desc')
            ->leftJoin('class_manages as cm', 'cm.id', '=', 'libraries.class')
            ->leftJoin('subjects', 'subjects.id', '=', 'libraries.subject')
            ->select('libraries.*', 'subjects.subject as subject_name', 'cm.class as class_name')
            ->where('libraries.quantity', '>', 0)
            // ->where('libraries.id', '=', $id)
            ->find($id);
        return view('library.edit', compact('library', 'book_type', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Library $library)
    {
        // validate the request
        $validator = Validator::make($request->all(), [
            'book_name' => 'required|string|max:255',
            'class' => 'required|integer',
            'subject' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'type' => 'required',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }

        // store book
        $library = Library::find($request->input('id'));
        $library->book_name = $request->input('book_name');
        $library->class = $request->input('class');
        $library->subject = $request->input('subject');
        $library->quantity = $request->input('quantity');
        $library->price = $request->input('price');
        $library->type = $request->input('type');
        $library->status = $request->input('status');

        $library->save();
        // session()->flash('success', 'Book updated successfully');
        return response()->json([
            'status' => true,
            'message' => 'Book updated successfully',
        ]);
    }

    /**
     * Sell the books or others.
     */
    public function sell()
    {
        // Current years session
        $classes = class_manage::get();
        return view('library.sell', compact('classes'));
    }

    // Get all the students after selecting the class
    function getStudents(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;
        // Get all the students
        $students = DB::table('students as s')
            ->join('registrations as r', 'r.id', '=', 's.registration_id')
            ->where('s.class_id', $request->class_id)
            ->where('s.status', '=', '1')
            ->where('s.session_id', '=', $session)
            ->orderBy('s.roll_no', 'asc')
            ->get(['r.name', 's.registration_id', 's.roll_no', 's.class_id', 's.section_id']);
        // Get all the subjects
        $subjects = DB::table('subjects as s')
            ->where('class_manages_id', '=', $request->class_id)
            // ->where('session_id', '=', $session)
            ->get(['id', 'subject']);
        return response()->json(['students' => $students, 'status' => true, 'subjects' => $subjects]);
    }

    public function getBooks(Request $request)
    {
        $books = DB::table('libraries')
            ->where('subject', '=', $request->subject_id)
            ->where('quantity', '>', '0')
            ->get(['id', 'book_name']);
        return response()->json([
            'status' => true,
            'books' => $books
        ]);
    }

    public function getBooksDetails(Request $request)
    {
        $booksDetails = DB::table('libraries')
            ->where('id', '=', $request->book_id)
            ->where('quantity', '>', '0')
            ->get(['quantity', 'price']);
        return response()->json([
            'status' => true,
            'booksDetails' => $booksDetails
        ]);
    }


    // Store sales
    public function store_sale(Request $request)
    {

        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;


        $data = $request->all();

        // Start from index 1 to skip the 0 index
        foreach ($data['subject'] as $key => $subjectId) {
            // Skip the 0 index
            if ($key == 0) {
                continue;
            }

            // Get the book ID and quantity for the current entry
            $bookId = $data['book'][$key] ?? null;
            $quantity = $data['quantity'][$key] ?? null;

            if ($bookId && $quantity) {
                // Update the libraries table to decrease the book quantity
                $library = Library::find($bookId);

                if ($library) {
                    if ($library->quantity >= $quantity) {
                        $library->quantity -= $quantity;
                        $library->save();
                    } else {
                        return response()->json([
                            'error' => "Insufficient quantity for book ID: {$bookId}"
                        ], 400);
                    }
                }
            }

            // Insert the sales record
            library_sale::create([
                'session' => $session,
                'class_id' => $data['class'],
                'registration_id' => $data['registration'],
                'subject_id' => $subjectId,
                'book_id' => $bookId,
                'quantity' => $quantity,
                'price' => $data['price'][$key] ?? null,
                'total' => $data['total'][$key] ?? null,
            ]);
        }

        session()->flash('success', 'Library sales data stored successfully!');
        return response()->json(['status' => true]);
    }

    // Sales Details
    public function salesDetails()
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $salesDetails = DB::table('library_sales as ls')
            ->where('ls.session', '=', $session)
            ->join('registrations as r', 'r.id', '=', 'ls.registration_id')
            ->join('class_manages as cm', 'cm.id', '=', 'ls.class_id')
            ->leftJoin('students as s', 's.registration_id', '=', 'ls.registration_id')
            ->select(
                DB::raw('MIN(ls.registration_id) as id'),
                'r.name',
                'cm.class',
                's.roll_no'
            )
            ->groupBy('cm.class', 's.roll_no', 'r.name')
            ->latest('ls.id')
            ->paginate(10);


        return view('library.sales-details', compact('salesDetails'));
    }

    // Sell details.............
    public function saleDetails(Request $request)
    {
        // Current session
        $session = DB::table('sessions')->where('active', '1')->get()[0]->id;

        $salesDetails = DB::table('library_sales as ls')
            ->where('ls.session', '=', $session)
            ->where('ls.registration_id', '=', $request->id)
            ->join('libraries as l', 'l.id', '=', 'ls.book_id')
            ->join('book_types as bt', 'bt.id', '=', 'l.type')
            ->select('l.book_name', 'ls.quantity', 'ls.price', 'ls.total', 'ls.created_at', 'bt.type')
            ->get();

        if ($salesDetails->isNotEmpty()) {
            $data = "";
            foreach ($salesDetails as $i => $salesDetail) {
                $data .= "<tr>
                <td>" . ($i + 1) . "</td>
                <td>" . $salesDetail->book_name . "</td>
                <td>" . $salesDetail->type . "</td>
                <td>" . $salesDetail->quantity . "</td>
                <td>" . $salesDetail->price . "</td>
                <td>" . $salesDetail->total . "</td>
                <td>" . \Carbon\Carbon::parse($salesDetail->created_at)->format('d-M-Y h:i:s A') . "</td>
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
}
