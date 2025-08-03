@extends('layouts.master')
@section('content')
    @push('title')
        <title>Paid Students</title>
    @endpush
    @php
        define('PAGE', 'paid_fees');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Paid Students</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Paid Students</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="student-group-form">
                <form action="{{ route('fees.paid') }}" method="get">
                    <div class="row">
                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <select class="form-control" name="class" id="selectedClass"
                                    aria-label="Default select example">
                                    <option selected value="">All Class</option>
                                    @if ($classes->isNotEmpty())
                                        @foreach ($classes as $class)
                                            <option {{ Request::get('class') == $class->id ? 'selected' : '' }}
                                                value="{{ $class->id }}">{{ $class->class }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="form-group">
                                <select class="form-control" id="month" name="month">
                                    <option value="" disabled selected>Select Month</option>
                                    <option {{ $month == 'January' ? 'selected' : '' }} value="January">January</option>
                                    <option {{ $month == 'February' ? 'selected' : '' }} value="February">February</option>
                                    <option {{ $month == 'March' ? 'selected' : '' }} value="March">March</option>
                                    <option {{ $month == 'April' ? 'selected' : '' }} value="April">April</option>
                                    <option {{ $month == 'May' ? 'selected' : '' }} value="May">May</option>
                                    <option {{ $month == 'June' ? 'selected' : '' }} value="June">June</option>
                                    <option {{ $month == 'July' ? 'selected' : '' }} value="July">July</option>
                                    <option {{ $month == 'August' ? 'selected' : '' }} value="August">August</option>
                                    <option {{ $month == 'September' ? 'selected' : '' }} value="September">September</option>
                                    <option {{ $month == 'October' ? 'selected' : '' }} value="October">October</option>
                                    <option {{ $month == 'November' ? 'selected' : '' }} value="November">November</option>
                                    <option {{ $month == 'December' ? 'selected' : '' }} value="December">December</option>
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
                                <a href="{{ route('fees.paid') }}" class="btn btn-primary">Reset</a>
                            </div>
                        </div>
                </form>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table
                                    class="table border-1 star-student table-hover table-center mb-0 datatable table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th width='5%'>SL</th>
                                            <th>Name</th>
                                            <th>Session</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>Roll No</th>
                                            <th>Paid Month</th>
                                            <th>Amount</th>
                                            <th>Paid Date</th>
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
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <span class="avatar avatar-sm me-2">
                                                            <img class="avatar-img"
                                                                src="{{ Storage::url('images/registration/' . $student->photo) }}"
                                                                alt="User Image">
                                                        </span>
                                                        <a>{{ $student->name }}</a>
                                                    </h2>
                                                </td>
                                                <td>{{ $student->class }}</td>
                                                <td>{{ $student->section ?? 'N/A' }}</td>
                                                <td>{{ $student->roll_no }}</td>
                                                <td>{{ $month }}</td>
                                                <td>{{ $student->amount }}</td>
                                                <td>{{ \Carbon\Carbon::parse($student->paid_date)->format('d-m-y , h:i:s') }}</td>
                                                <td><span class="badge badge-success">Paid</span></td>
                                                <td class="text-center">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="true"><i class="fas fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-end" data-popper-placement="bottom-end" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 22px, 0px);">
                                                            <a class="dropdown-item" href="{{route('fees.add',['id'=>$student->id])}}"><i class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Fees</a>
                                                            <a class="dropdown-item" href="{{ route('fees.details', ['id' => $student->id]) }}"><i class="fa fa-eye me-2" aria-hidden="true"></i>Details</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
