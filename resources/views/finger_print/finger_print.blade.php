@extends('layouts.master')
@section('content')
@push('title')
<title>Students List</title>
@endpush
@php
define('PAGE', 'finger');
@endphp
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-sub-header">
                        <h3 class="page-title">Students</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">All Students</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="student-group-form">
            <form action="{{ route('finger.list') }}" method="get">
                <div class="row">
                    <div class="col-lg-2 col-md-6">
                        <div class="form-group">
                            <select class="form-control" name="class" id="selectedClass"
                                aria-label="Default select example">
                                <option selected value="">Select class</option>
                                @if ($classes->isNotEmpty())
                                @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @if (Request::has('class') &&
                                    Request::get('class')==$class->id) selected @endif>
                                    {{ $class->class }}
                                </option>
                                @endforeach
                                @endif
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
                            <a href="{{ route('finger.list') }}" class="btn btn-primary">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table comman-shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table border-1 star-student table-hover table-center mb-0 table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th width='5%'>SL</th>
                                        <th>Registration ID</th>
                                        <th>Name</th>
                                        <th>Session</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                        <th>Roll No</th>
                                        <th>Mobile</th>
                                        <th>Add Finger</th>
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
                                        <td>{{ $student->mobile }}</td>
                                        <td><a href="{{ route('finger.add', ['id' => $student->id]) }}"
                                                class="btn btn-sm btn-primary text-light">Add Finger</a></td>
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
@section('customJS')
<script>
    $(document).ready(function() {
            $('.check').change(function() {
                var status = this.checked ? '1' : '0';
                var studentId = this.id.replace('status', '');
                $.ajax({
                    url: '{{ route('status.students') }}',
                    method: 'post',
                    data: {
                        student_id: studentId,
                        status: status,
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#overlayer').show();
                    },
                    success: function(response) {
                        $('#overlayer').hide();
                        var message = response.message;
                        if (response.status) {
                            toastr.success(message);
                        } else {
                            toastr.error(message);
                        }
                    },
                    error: function(error) {
                        console.error('Error occurred:', error);
                    }
                });
            });
        });
</script>
@endsection
