@extends('layouts.master')
@section('content')
    @push('title')
        <title>Hostelles Fill Attendance</title>
    @endpush
    @php
        define('PAGE', 'hostel_fill_attendance');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Fill Attendance</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Fill Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">

                            <div class="row">
                                {{-- Attendance Panels --}}
                                <div class="card card-primary">
                                    <div class="page-header">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <div class="card-header">
                                                    <h5 class="card-title">Attendance Panels</h5>
                                                </div>
                                            </div>
                                            <div class="col-auto text-end float-end ms-auto download-grp">
                                                <a href="{{ route('hostel.attendance.view') }}" class="btn btn-primary">View
                                                    Attendance</a>
                                            </div>
                                        </div>
                                    </div>
                                    <form action="{{ route('hostel.attendance.fill') }}" method="get">
                                        <div class="row">
                                            <div class="col-md-2 ">
                                                <div class="form-group">
                                                    <label>Class<font style="color:red"><b>*</b></font></label>
                                                    <select class="form-control" name="class" id="selectedClass"
                                                        aria-label="Default select example">
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
                                                                    value="{{ $section->id }}">{{ $section->section }}
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            <option selected value="">Choose Section</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Date :</label>
                                                        <input type="text" class="form-control datetimepicker"
                                                            name="attendance_date" id="attendance_date" placeholder="Select date">
                                                    </div>
                                                </div> --}}
                                            {{-- <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Order By<font style="color:red"><b>*</b></font></label>
                                                        <select class="form-control" name="order_by" id="order_by">
                                                            <option value="order by student_name ASC">Student Name(ASC)
                                                            </option>
                                                            <option value="order by student_name DESC">Student Name(DESC)
                                                            </option>
                                                            <option value="order by CAST(student_roll_number as SIGNED) ASC">
                                                                Roll Number(ASC)</option>
                                                            <option value="order by CAST(student_roll_number as SIGNED) DESC">
                                                                Roll Number(DESC)</option>
                                                            <option
                                                                value="order by CAST(student_admission_number as SIGNED) DESC">
                                                                Admission Number(DESC)</option>
                                                            <option
                                                                value="order by CAST(student_admission_number as SIGNED) ASC">
                                                                Admission Number(ASC)</option>
                                                        </select>
                                                    </div>
                                                </div> --}}
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <br>
                                                    <button class="btn btn-success ">Fill
                                                        Attendance</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row table-responsive m-1" style="margin-top:20px">
                                        <table border="2" class="table table-bordered table-striped" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Sl No</th>
                                                    <th>Student Name</th>
                                                    <th>Father's Name</th>
                                                    <th>Student Attendance</th>
                                                    <th>Filled Attendance</th>
                                                    <th>Class/Section/Roll No</th>
                                                    <th>Mobile</th>
                                                    <th>Attendance Date/Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($students != '')
                                                    @foreach ($students as $key => $student)
                                                        <tr>
                                                            <td>{{ ++$key }}</td>
                                                            {{-- <td>{{ $student->id }}</td> --}}
                                                            <td>{{ $student->name }}</td>
                                                            <td>{{ $student->fathersName }}</td>
                                                            <td>
                                                                <select
                                                                    onchange="fill_attendance('{{ $student->id }}','{{ $student->session }}','{{ $student->class_id }}','{{ $student->section }}','{{ $student->roll_no }}',this.value , 'Manual');"
                                                                    class="form-control">
                                                                    <option selected value="">None</option>
                                                                    <option
                                                                        {{ $student->attendance == 'P' ? 'selected' : '' }}
                                                                        value="P">P</option>
                                                                    <option
                                                                        {{ $student->attendance == 'A' ? 'selected' : '' }}
                                                                        value="A">A</option>
                                                                </select>
                                                            </td>
                                                            <td class="text-center">{{ $student->attendance ?? '' }}
                                                            </td>
                                                            <td>{{ $student->class }} /
                                                                {{ $student->section ?? 'N/A' }} /
                                                                {{ $student->roll_no }}</td>
                                                            <td>{{ $student->mobile }}</td>
                                                            <td>
                                                                <input type="text"
                                                                    value="{{ $student->updated_at !== null ? \Carbon\Carbon::parse($student->updated_at)->format('d-m-Y h:i:s A') : '' }}"
                                                                    style="border:none;" readonly=""
                                                                    class="form-control">
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
                        // console.log(response);
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

        function fill_attendance(registration_id, session, className, section, roll, value ,type) {
            if (value !== '') {
                var data = {
                    registration_id: registration_id,
                    session: session,
                    class: className,
                    section: section ?? '',
                    roll: roll,
                    attendance: value,
                    attendance_type: type,
                }
                var currentSelect = $(event.target);
                $.ajax({
                    url: "{{ route('hostel.attendance.fill_attendnce') }}",
                    type: "post",
                    data: data,
                    dataType: "json",
                    beforeSend: function() {
                        $("#overlayer").show();
                    },
                    success: function(response) {
                        $("#overlayer").hide();
                        // console.log(response);
                        if (response.status) {
                            toastr.success(response.message);
                            var attendanceValue = response.attendance;
                            var currentDate = response.time;
                            currentSelect.closest('td').next('td').text('').text(attendanceValue);
                            currentSelect.closest('td').parent().find('td:last').find('input').val('').val(currentDate);
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#overlayer").hide();
                        console.error('Error occurred while fetching attendance:', xhr, status, error);
                    }
                })
            }
        }
    </script>
@endsection
