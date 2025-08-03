@extends('layouts.master')
@section('content')
    @push('title')
        <title>Hostellers View Attendance</title>
    @endpush
    @php
        define('PAGE', 'hostel_view_attendance');
    @endphp
    {{-- message --}}
    {{-- {!! Toastr::message() !!} --}}
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">View Attendance</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">View Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">

                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="card-header">
                                            <h5 class="card-title">Attendance Details</h5>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('hostel.attendance.fill') }}" class="btn btn-primary">Fill Attendance</a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Students Details --}}
                                <div class="card card-primary">
                                    <div class="card-body" id="attendance_detail">
                                        <style>
                                            select {
                                                -webkit-appearance: none;
                                                -moz-appearance: none;
                                                text-indent: 1px;
                                                text-overflow: '';
                                            }
                                        </style>
                                        <form action="{{ route('hostel.attendance.view') }}" method="get">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label>Year <font style="color:red"><b>*</b></font></label>
                                                    <select required name="attendance_year" id="attendance_year"
                                                        class="form-control">
                                                        <option selected="" value="">Select Year</option>
                                                        @foreach ($years as $year)
                                                            <option
                                                                {{ Request::get('attendance_year') == $year ? 'selected' : '' }}
                                                                value='{{ $year }}'>{{ $year }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Month <span class="text-danger selectLabel"></span> </label>
                                                    <select required name="attendance_month" id="attendance_month"
                                                        class="form-control">
                                                        <option value="">Select month</option>
                                                        @if (Request::get('attendance_month'))
                                                            <option selected value="{{ Request::get('attendance_month') }}">
                                                                {{ Request::get('attendance_month') }}</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-2 ">
                                                    <div class="form-group">
                                                        <label>Class<font style="color:red"><b>*</b></font></label>
                                                        <select required class="form-control" name="class"
                                                            id="selectedClass" aria-label="Default select example">
                                                            <option selected value="">Choose class</option>
                                                            @if ($classes->isNotEmpty())
                                                                @foreach ($classes as $class)
                                                                    <option
                                                                        {{ Request::get('class') == $class->id ? 'selected' : '' }}
                                                                        value="{{ $class->id }}">{{ $class->class }}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Section <span class="text-danger selectLabel"></span></label>
                                                        <select class="form-control" name="section" id="section"
                                                            optional="true" aria-label="Default select example">
                                                            @if (Request::get('section'))
                                                                @foreach ($sections as $section)
                                                                    <option
                                                                        {{ Request::get('section') == $section->id ? 'selected' : '' }}
                                                                        value="{{ $section->id }}">
                                                                        {{ $section->section }}
                                                                    </option>
                                                                @endforeach
                                                            @else
                                                                <option selected value="">Choose Section</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label>
                                                        <br>
                                                        <button type="submit" class="btn btn-info text-light">View
                                                            Attendance</button>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <br>
                                                    <button style="width:100px;" type="button"
                                                        class="btn btn-primary">Present</button>
                                                    <button style="width:100px;" type="button"
                                                        class="btn btn-danger">Absent</button>
                                                    <button style="width:100px;" type="button"
                                                        class="btn btn-success">Sunday</button>
                                                    <button style="width:100px;" type="button"
                                                        class="btn btn-info text-light">Not Filled</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="row table-responsive" style="margin-top:20px">
                                            <table id="example1" class="table table-bordered table-striped"
                                                style="width: 1400px; overflow: auto;">
                                                <thead class="my_background_color">
                                                    <tr>
                                                        <th>Student Name</th>
                                                        <th><span
                                                                id="by_month">{{ $data['year'] }}-{{ $data['month'] }}</span>
                                                            Month Attendance</th>
                                                        <th>Class : {{ $data['class'] }} | Section :
                                                            {{ $data['section'] ? $data['section'] : 'All' }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="stud_list">
                                                    <tr>
                                                        <td><button type="button" class="btn btn-success"
                                                                style="font-size:14px;">Date : </button></td>
                                                        <td colspan="2">
                                                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                                                <button
                                                                    style="width:25px;padding:2px;height:25px;font-size:14px"
                                                                    type="button"
                                                                    class="btn btn-warning">{{ $day }}</button>
                                                            @endfor

                                                            <button
                                                                style="width:50px;padding:1px;font-size:12px;height:25px;"
                                                                type="button" class="btn btn-primary">Present</button>
                                                            <button
                                                                style="width:50px;padding:1px;font-size:12px;height:25px;"
                                                                type="button" class="btn btn-danger">Absent</button>
                                                            <button
                                                                style="width:50px;padding:1px;font-size:12px;height:25px;"
                                                                type="button" class="btn btn-success">Sunday</button>
                                                            <button
                                                                style="width:50px;padding:1px;font-size:12px;height:25px;"
                                                                type="button" class="btn btn-info text-light">Not
                                                                Fill</button>
                                                        </td>
                                                    </tr>
                                                    @if ($formattedData)
                                                        @foreach ($formattedData as $name => $attendanceData)
                                                            <tr>
                                                                <td style="font-size:14px;">{{ $name }}</td>
                                                                <td colspan="2">
                                                                    @foreach ($attendanceData['attendance'] as $attendance)
                                                                        @if ($attendance['attendance'] == 'P')
                                                                            <input type="text" data-bs-toggle="tooltip"
                                                                                data-bs-placement="top"
                                                                                data-bs-original-title="{{ \Carbon\Carbon::parse($attendance['created_at'])->format('d-M-Y h:i:s')}}, By:{{ $attendance['attendance_by'] }}, Type:{{ $attendance['attendance_type'] }}, From:{{ $attendance['attendance_from'] }}"
                                                                                value="{{ $attendance['attendance'] }}"
                                                                                class="form-control btn-default text-center text-light"
                                                                                readonly
                                                                                style="width:25px;padding:2px;height:25px;font-size:14px;display: inline;border:1px solid blue; background:rgb(35, 90, 200)">
                                                                        @elseif ($attendance['attendance'] == 'A')
                                                                            <input type="text" data-bs-toggle="tooltip"
                                                                                data-bs-placement="top"
                                                                                data-bs-original-title="{{ \Carbon\Carbon::parse($attendance['created_at'])->format('d-M-Y h:i:s')}}, By:{{ $attendance['attendance_by'] }}, Type: No, From:{{ $attendance['attendance_from'] }}"
                                                                                value="{{ $attendance['attendance'] }}"
                                                                                class="form-control btn-default text-center text-light"
                                                                                readonly
                                                                                style="width:25px;padding:2px;height:25px;font-size:14px;display: inline;border:1px solid red; background:red">
                                                                        @elseif ($attendance['attendance'] == 'S')
                                                                            <input type="text"
                                                                                value="{{ $attendance['attendance'] }}"
                                                                                class="form-control btn-default text-center text-light"
                                                                                readonly
                                                                                style="width:25px;padding:2px;height:25px;font-size:14px;display: inline;border:1px solid green; background:rgb(77, 202, 77)">
                                                                        @elseif ($attendance['attendance'] == 'N')
                                                                            <input type="text" value=""
                                                                                class="form-control btn-default text-center text-light"
                                                                                readonly
                                                                                style="width:25px;padding:2px;height:25px;font-size:14px;display: inline;border:1px solid rgb(51, 167, 225); background:rgb(51, 167, 225)">
                                                                        @endif
                                                                    @endforeach
                                                                    <button
                                                                        style="width:50px;font-size:14px;height:25px;padding:2px;"
                                                                        type="button"
                                                                        class="btn btn-primary">{{ $attendanceData['summary_counts']['P'] }}</button>
                                                                    <button
                                                                        style="width:50px;font-size:14px;height:25px;padding:2px;"
                                                                        type="button"
                                                                        class="btn btn-danger">{{ $attendanceData['summary_counts']['A'] }}</button>
                                                                    <button
                                                                        style="width:50px;font-size:14px;height:25px;padding:2px;"
                                                                        type="button"
                                                                        class="btn btn-success">{{ $attendanceData['summary_counts']['S'] }}</button>
                                                                    <button
                                                                        style="width:50px;font-size:14px;height:25px;padding:2px;"
                                                                        type="button"
                                                                        class="btn btn-info text-light">{{ $attendanceData['summary_counts']['N'] }}</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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

            // Select the month
            $('#attendance_year').on('change', function() {
                var year_id = $(this).val();
                $.ajax({
                    url: "{{ route('getAttendanceYear') }}",
                    type: "get",
                    data: {
                        year_id: year_id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#attendance_month').find('option').text('Loading...');
                    },
                    success: function(response) {
                        console.log(response);

                        // Section get and set after selecting the class
                        $('#attendance_month').find('option').text('Select Month');
                        if (response.status) {
                            $('#attendance_month').find('option').not(':first').remove();
                            $('.selectLabel').text('').append('*');
                            $.each(response["months"], function(key, value) {
                                $('#attendance_month').append(
                                    `<option value='${value}'>${value}</option>`
                                );
                            });

                            $('#attendance_month').attr('optional', 'false');
                        } else {
                            $('.selectLabel').text('');
                            $('#attendance_month').find('option').not(':first').remove();
                            $('#attendance_month').attr('optional', 'true');
                        }
                    }
                });
            });
        });
    </script>
@endsection
