public function due(Request $request)
    {
        // Get current session ID
        $session = DB::table('sessions')->where('active', '1')->first()->id;
        // return $request->input('fees_type');
        // Get all classes
        $classes = class_manage::get();

        // Get fees amount based on price type and class
        $fees_amount = DB::table('set_prices')
            ->where('price_type', $request->fees_type)
            ->where('class_id', $request->class)
            ->select('price_type', 'prices')
            ->first();

        $total_fee = $fees_amount ? (int) $fees_amount->prices : 0; // Convert to integer

        //! Get all students of the selected class in the current session
        $selectedStudents = DB::table('students')
            ->where('session_id', '=', $session)
            ->where('class_id', $request->class)
            ->pluck('registration_id') // Extract only IDs
            ->toArray();

        //! Get student admission details with their fee status for the selected month
        $studentData = DB::table('registrations as r')
            ->leftJoin('students as s', 'r.id', '=', 's.registration_id')
            ->leftJoin('fees as f', 'r.id', '=', 'f.s_id') // Keep join simple
            ->join('class_manages as cm', 'cm.id', '=', 's.class_id')
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
                'f.amount',
                'f.status',
                'cm.class'
            )
            ->whereIn('s.registration_id', $selectedStudents);
            // ->where($request->input('fees_type') == 'Yes');

        // Ignore students admitted after the requested month
        if ($request->month) {
            $requestMonth = date('Y-m-01', strtotime($request->month . '-01')); // Convert request month to YYYY-MM-DD
            $studentData = $studentData->where('s.created_at', '<', $requestMonth);
        }


        // Exclude `month` filter for "Admission Fees" and "Exam Fees"
        if (!in_array($request->fees_type, ['Admission Fees', 'Exam Fees'])) {
            $studentData = $studentData->where('f.month', '=', $request->month);
        }

        // Always filter by `fees_type`
        $studentData = $studentData->where('f.fees_type', '=', $request->fees_type);

        $studentData = $studentData->get();

        //! Modify student status and calculate due amounts
        $students = $studentData->map(function ($student) use ($total_fee) {
            if ($student->status === 'Paid') {
                $student->status_label = 'Paid';
                $student->due_amount = 0; // No due amount for paid students
            } elseif ($student->status === 'Due') {
                $student->status_label = 'Due';
                $student->due_amount = $total_fee - (int) $student->amount; // Subtract paid amount
            } else {
                $student->status_label = 'Not Present';
                $student->due_amount = $total_fee; // Full amount due if not present
            }
            return $student;
        });

        //! Filter students based on request status
        if ($request->status == 'Paid') {
            $students = $students->where('status_label', 'Paid');
        } else {
            $students = $students->whereIn('status_label', ['Due', 'Not Present']);
        }
        // return $students;
        $months = $request->month ?? Carbon::now()->monthName;

        //! Return the data to the view
        return view('fees.due_fees', compact('classes', 'students','months'));
    }





    public function due(Request $request)
    {
        // Get current session ID
        $session = DB::table('sessions')->where('active', '1')->first()->id;

        // Get all classes
        $classes = class_manage::get();

        // Get fees amount based on price type and class
        $fees_amount = DB::table('set_prices')
            ->where('price_type', $request->fees_type)
            ->where('class_id', $request->class)
            ->select('price_type', 'prices')
            ->first();

        $total_fee = $fees_amount ? (int) $fees_amount->prices : 0; // Convert to integer

        //! Get all students of the selected class in the current session
        $students = DB::table('registrations as r')
            ->leftJoin('students as s', 'r.id', '=', 's.registration_id')
            ->leftJoin('fees as f', function ($join) use ($request) {
                $join->on('r.id', '=', 'f.s_id')
                    ->where('f.fees_type', '=', $request->fees_type);
            }) // Ensure we fetch all students, even if they have no fees record
            ->join('class_manages as cm', 'cm.id', '=', 's.class_id')
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
                'f.amount',
                'f.status',
                'f.month',
                'f.fees_type',
                'cm.class'
            )
            ->where('s.session_id', '=', $session)
            ->where('s.class_id', '=', $request->class);

        // Ignore students admitted after the requested month
        if ($request->month) {
            $requestMonth = date('Y-m-01', strtotime($request->month . '-01')); // Convert request month to YYYY-MM-DD
            $students = $students->where('s.created_at', '<', $requestMonth);
        }

        // Exclude `month` filter for "Admission Fees" and "Exam Fees"
        if (!in_array($request->fees_type, ['Admission Fees', 'Exam Fees'])) {
            $students = $students->where(function ($query) use ($request) {
                $query->whereNull('f.month') // Include students without a fees record
                    ->orWhere('f.month', '=', $request->month);
            });
        }

        $students = $students->get();

        //! Merge payments for the same student and mark missing students as "Due"
        $students = $students->groupBy('id')->map(function ($payments) use ($total_fee) {
            // Take the first entry as base
            $student = $payments->first();

            // Sum all amounts paid (ignore null values)
            $totalPaid = $payments->sum(function ($payment) {
                return (int) ($payment->amount ?? 0);
            });

            // Determine due amount
            $dueAmount = max(0, $total_fee - $totalPaid);

            // Set final status label
            if ($dueAmount == 0) {
                $student->status_label = 'Paid';
            } elseif ($student->amount === null) { // If student is not in fees table
                $student->status_label = 'Due';
                $student->amount = 0; // No payment made
                $student->due_amount = $total_fee; // Full fee is due
            } else {
                $student->status_label = 'Due';
                $student->due_amount = $dueAmount;
            }

            return $student;
        })->values(); // Reset array keys

        //! Filter students based on request status
        if ($request->status == 'Paid') {
            $students = $students->where('status_label', 'Paid');
        } else {
            $students = $students->whereIn('status_label', ['Due']);
        }

        // Get month name
        $months = $request->month ?? Carbon::now()->monthName;

        //! Return the data to the view
        return view('fees.due_fees', compact('classes', 'students', 'months'));
    }
