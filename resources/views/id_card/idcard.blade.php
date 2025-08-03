@extends('layouts.master')
@section('content')
    @push('title')
        <title>ID Card</title>
    @endpush
    @php
        define('PAGE', 'id_card');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Student ID Card</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                <li class="breadcrumb-item active">Student ID Card</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="student-group-form">
                <form action="{{ route('student.idcard') }}" method="get">
                    <div class="row">
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <select class="form-control" name="class" id="selectedClass"
                                    aria-label="Default select example">
                                    <option selected value="">All classes</option>
                                    @if ($classes->isNotEmpty())
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" @if (Request::has('class') && Request::get('class') == $class->id) selected @elseif ($loop->first) selected @endif>
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
                                    placeholder="Search by Name ...">
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
                                <a href="{{ route('student.idcard') }}" class="btn btn-primary">Reset</a>
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
                                        <h3 class="page-title">Students List</h3>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="table-responsive">
                                <table
                                    class="table border-1 star-student table-hover table-center mb-0 table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th width='5%'>SL</th>
                                            <th>Registration ID</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>DOB</th>
                                            <th>Parent's Name</th>
                                            <th>Mobile</th>
                                            <th>ID Card</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($regStudents as $key => $student)
                                            <tr>
                                                <td>{{ ($regStudents->currentPage() - 1) * $regStudents->perPage() + $loop->iteration }}</td>
                                                <td>{{ config('website.registration') . $student->id }}
                                                </td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <span class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle"
                                                                src="{{ asset('uploads/images/registration/' . $student->photo) }}"
                                                                alt="User Image">
                                                        </span>
                                                        <a>{{ $student->name }}</a>
                                                    </h2>
                                                </td>
                                                <td>{{ $student->class }}</td>
                                                <td>{{ $student->section ?? 'N/A' }}</td>
                                                <td>{{ $student->dateOfBirth }}</td>
                                                <td>{{ $student->fathersName }}</td>
                                                <td>{{ $student->mobile }}</td>
                                                <td class="text-center">
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Print ID Card"
                                                        href="{{ route('student.id.print', ['id' => $student->id]) }}"
                                                        target="_blank"><i class="fa fa-print text-primary me-2"></i></a>
                                                    {{-- <a data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Download ID Card"
                                                        href="{{ route('student.id.download', ['id' => $student->id]) }}"><i
                                                            class="fa fa-download text-danger me-2"></i></a> --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $regStudents->appends(request()->query())->links() }}
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
            // Selected class
            $('#selectedClass').on('change', function() {
                var class_id = $(this).val();
                $.ajax({
                    url: "{{ route('getSection') }}",
                    type: "get",
                    data: {
                        class_id: class_id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#section').find('option').text('Loading...');
                    },
                    success: function(response) {
                        // Section get and set after selecting the class
                        $('#section').find('option').text('Select section');
                        if (response.status) {
                            $('#section').find('option').not(':first').remove();
                            $('.selectLabel').text('').append('*');
                            $.each(response["sections"], function(key, value) {
                                $('#section').append(
                                    `<option value='${value.id}'>${value.section}</option>`
                                );
                            });
                            $('#section').attr('optional', 'false');
                        } else {
                            $('.selectLabel').text('');
                            $('#section').find('option').not(':first').remove();
                            $('#section').attr('optional', 'true');
                        }
                    }
                });
            });
        });
    </script>
@endsection
