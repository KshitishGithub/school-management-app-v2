@extends('layouts.master')
@section('content')
    @push('title')
        <title>IN OUT Record</title>
    @endpush
    @php
        define('PAGE', 'in_out_record');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">In Out Record</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                <li class="breadcrumb-item active">In Out Record</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="student-group-form">
                <form action="{{ route('hostel.inOutRecord') }}" method="get">
                    <div class="row">
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <select class="form-control" name="class" id="selectedClass"
                                    aria-label="Default select example">
                                    <option selected value="">All classes</option>
                                    @if ($classes->isNotEmpty())
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}"
                                                @if (Request::has('class') && Request::get('class') == $class->id) selected @elseif ($loop->first) selected @endif>
                                                {{ $class->class }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" value="{{ Request::get('name') }}"
                                    placeholder="Search by name ...">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="father_name"
                                    value="{{ Request::get('father_name') }}" placeholder="Search by father name ...">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="mobile"
                                    value="{{ Request::get('mobile') }}" placeholder="Search by Phone ...">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="search-student-btn">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="search-student-btn">
                                <a href="{{ route('hostel.inOutRecord') }}" class="btn btn-primary">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table comman-shadow">
                        <div class="card-body">
                            {{-- <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">Students</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="#" class="btn btn-outline-primary me-2"><i
                                                class="fas fa-download"></i> Download</a>
                                    </div>
                                </div>
                            </div> --}}

                            <div class="table-responsive">
                                <table class="table border-1 star-student table-hover table-center mb-0 table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th width='5%'>SL</th>
                                            <th>In/Out</th>
                                            <th>Registration ID</th>
                                            <th>Name</th>
                                            <th>Session</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>Roll No</th>
                                            <th>Parent's Name</th>
                                            <th>Mobile</th>
                                            <th>Date-Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $key => $student)
                                            <tr>
                                                <td>{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
                                                <td>
                                                    @if ($student->attendance == 'IN')
                                                        <span class="badge badge-success">IN</span>
                                                    @else
                                                        <span class="badge badge-danger">OUT</span>
                                                    @endif
                                                </td>
                                                </td>
                                                <td>{{ config('website.registration') . $student->id }}
                                                </td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <span class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-squre"
                                                                src="{{ asset('uploads/images/registration/' . $student->photo) }}"
                                                                alt="User Image">
                                                        </span>
                                                        <a>{{ $student->name }}</a>
                                                    </h2>
                                                </td>
                                                <td>{{ $student->session }}</td>
                                                <td>{{ $student->class }}</td>
                                                <td>{{ $student->section ?? 'N/A' }}</td>
                                                <td>{{ $student->roll_no }}</td>
                                                <td>{{ $student->fathersName }}</td>
                                                <td>{{ $student->mobile }}</td>
                                                <td>{{ \Carbon\Carbon::parse($student->created_at)->format('d-m-Y h:i A') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $students->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
