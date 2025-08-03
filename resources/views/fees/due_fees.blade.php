@extends('layouts.master')
@section('content')
@push('title')
<title>Due Students</title>
@endpush
@php
define('PAGE', 'due_fees');
@endphp
{{-- message --}}
{{-- {!! Toastr::message() !!} --}}
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Due Students</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                        <li class="breadcrumb-item active">Due Students</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="student-group-form">
            <form action="{{ route('fees.due') }}" method="get">
                <div class="row">
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <select class="form-control" name="class" id="selectedClass"
                                aria-label="Default select example" required>
                                <option selected disabled value="">Select class</option>
                                @if ($classes->isNotEmpty())
                                @foreach ($classes as $class)
                                <option {{ Request::get('class')==$class->id ? 'selected' : '' }}
                                    value="{{ $class->id }}">{{ $class->class }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <select class="form-control" id="month" required name="month">
                                @foreach ($monthsList as $month)
                                <option value="{{ $month }}" {{ $month==$selectedMonth ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <select class="form-control" name="fees_type" id="fees_type"
                                aria-label="Default select example" required>
                                <option disabled value="" {{ request('fees_type')=='' ? 'selected' : '' }}>Select Fees
                                    Type</option>
                                <option value="School Fees" {{ request('fees_type')=='School Fees' ? 'selected' : '' }}>
                                    School Fees</option>
                                <option value="Transport Fees" {{ request('fees_type')=='Transport Fees' ? 'selected'
                                    : '' }}>Transport Fees</option>
                                <option value="Hostel Fees" {{ request('fees_type')=='Hostel Fees' ? 'selected' : '' }}>
                                    Hostel Fees</option>
                                <option value="Mess Fees" {{ request('fees_type')=='Mess Fees' ? 'selected' : '' }}>Mees
                                    Fees</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <select class="form-control" name="status" id="status" aria-label="Default select example"
                                required>
                                <option disabled value="" {{ request('status')=='' ? 'selected' : '' }}>Select fees
                                    status</option>
                                <option value="Paid" {{ request('status')=='Paid' ? 'selected' : '' }}>Paid</option>
                                <option value="Due" {{ request('status')=='Due' ? 'selected' : '' }}>Due</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="search-student-btn">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="search-student-btn">
                            <a href="{{ route('fees.due') }}" class="btn btn-primary">Reset</a>
                        </div>
                    </div>
            </form>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="page-header pb-0 mb-0">
                        <div class="row align-items-center">
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                {{-- <a href="#" id="downloadDuePdfBtn" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-download"></i> Download PDF
                                </a> --}}
                                <a href="#" id="downloadDueCsvBtn" class="btn btn-outline-primary me-2">
                                    <i class="fas fa-download"></i> Download CSV
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border-1 star-student table-hover table-center mb-0 table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th width='5%'>SL</th>
                                        <th>Registration</th>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                        <th>Roll No</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $key => $student)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ config('website.registration') . $student->id }}
                                        </td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->class }}</td>
                                        <td>{{ $student->section ?? 'N/A' }}</td>
                                        <td>{{ $student->roll_no }}</td>
                                        <td>{{ $student->amount }}</td>
                                        <td>{{ $student->due_amount ?? 0 }}</td>
                                        @if ($student->status_label == 'Paid')
                                        <td><span class="badge badge-success">{{ $student->status_label }}</span></td>
                                        @else
                                        <td><span class="badge badge-danger">{{ $student->status_label }}</span></td>
                                        @endif
                                        <td class="text-center">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="true"><i
                                                        class="fas fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end"
                                                    data-popper-placement="bottom-end"
                                                    style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 22px, 0px);">
                                                    <a class="dropdown-item"
                                                        href="{{route('fees.add',['id'=>encrypt($student->id)])}}"><i
                                                            class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add
                                                        Fees</a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('fees.details', ['id' => encrypt($student->id)]) }}"><i
                                                            class="fa fa-eye me-2" aria-hidden="true"></i>Details</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- {{ $students->appends(request()->query())->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('customJS')
<script>
    // CSV Download
    document.getElementById('downloadDueCsvBtn').addEventListener('click', function () {
        const selectedClass = document.getElementById('selectedClass');
        const selectedClassText = selectedClass.options[selectedClass.selectedIndex].text || 'N/A';

        const selectedMonth = document.getElementById('month')?.value || 'N/A';
        const feesType = document.getElementById('fees_type')?.value || 'Fees';
        const status = document.getElementById('status')?.value || '';

        const capitalizedMonth = selectedMonth.charAt(0).toUpperCase() + selectedMonth.slice(1);
        const heading = `Class ${selectedClassText} ${feesType} of ${capitalizedMonth} ${status}`;

        const table = document.querySelector('table');
        const rows = [...table.rows].map(row =>
            [...row.cells].map(cell => `"${cell.innerText.replace(/"/g, '""')}"`).join(',')
        ).join('\n');

        const csvContent = "data:text/csv;charset=utf-8," + heading + "\n\n" + rows;

        const link = document.createElement('a');
        link.setAttribute('href', encodeURI(csvContent));
        link.setAttribute('download', `${heading}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });


    // PDF Download
    {{--  document.getElementById('downloadDuePdfBtn').addEventListener('click', function (e) {
        const selectedClass = document.getElementById('selectedClass').value;
        const selectedMonth = document.getElementById('month').value;
        const selectedFeesType = document.getElementById('fees_type').value;
        const selectedStatus = document.getElementById('status').value;

        if (!selectedClass || !selectedMonth || !selectedFeesType || !selectedStatus) {
            alert("Please select all filter options before downloading the PDF.");
            e.preventDefault();
            return;
        }

        const url = `{{ route('fees.due.downloadPDF') }}?class=${selectedClass}&month=${selectedMonth}&fees_type=${selectedFeesType}&status=${selectedStatus}`;
        window.location.href = url;
    });  --}}
</script>


@endsection
