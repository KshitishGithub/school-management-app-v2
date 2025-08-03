@extends('layouts.master')
@section('content')
    @push('title')
        <title>Hostellers Fill Attendance</title>
    @endpush
    @php
        define('PAGE', 'hostel_fingerprint_attendance');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Fingerprint Attendance System</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Fingerprint Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="row">
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
                                    <form action="{{ route('hostel.fingerprint.fill') }}" method="get">
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
                                                            <option selected value="">Choose Subjects</option>
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
                                                    <th>SL No</th>
                                                    <th>Student Name</th>
                                                    <th>Father's Name</th>
                                                    <th>Class/Section/Roll No</th>
                                                    <th>Mobile</th>
                                                    <th>Attendance</th>
                                                    <th>Filled Attendance</th>
                                                    <th>Attendance Date/Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($students != '')
                                                    @foreach ($students as $key => $student)
                                                        <tr>
                                                            <td>{{ ++$key }}</td>
                                                            <td>{{ $student->name }}</td>
                                                            <td>{{ $student->fathersName }}</td>
                                                            <td>{{ $student->class }} /
                                                                {{ $student->section ?? 'N/A' }} /
                                                                {{ $student->roll_no }}</td>
                                                            <td>{{ $student->mobile }}</td>
                                                            <td class="text-center"><i
                                                                    class="fas fa-fingerprint btn btn-primary"
                                                                    onclick="openModal({{ $student->id }})"></i></td>
                                                            <td id="attendance_{{ $student->id }}" class="text-center">
                                                                {{ $student->attendance ?? '' }}</td>
                                                            <td> <input type="text" id="time_{{ $student->id }}"
                                                                    value="{{ $student->updated_at !== null ? \Carbon\Carbon::parse($student->updated_at)->format('d-m-Y h:i:s A') : '' }}"
                                                                    style="border:none;" readonly=""
                                                                    class="form-control"></td>
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
    {{-- Modal --}}
    <div id="ModalData">

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

        function fill_attendance(registration_id, session, className, section, roll, value , type) {
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
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $("#overlayer").show();
                    },
                    success: function(response) {
                        $("#overlayer").hide();
                        // console.log(response);
                        if (response.status) {
                            $("#fingers_modal").modal('hide');
                            toastr.success(response.message);

                            var attendanceValue = response.attendance;
                            var currentDate = response.time;
                            $('#attendance_' + registration_id).text('').text(attendanceValue);
                            $('#time_' + registration_id).val('').val(currentDate);
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

    {{-- Finger Print --}}
    <script src="{{ url('assets/finger_print/js/mfs100.js') }}"></script>
    <script src="{{ url('assets/finger_print/js/jquery-1.8.2.js') }}"></script>

    <script>
        var quality = 60;
        var timeout = 10;
        var nooffinger = '1';

        // Open modal for fingerprint
        function openModal(studentId) {
            $.ajax({
                url: "{{ route('fingerprint.modal') }}",
                type: "post",
                data: {
                    studentId: studentId,
                },
                beforeSend: function() {
                    $("#overlayer").show();
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "html",
                success: function(response) {
                console.log(response);
                    $("#overlayer").hide();
                    $("#ModalData").html('');
                    $("#ModalData").html(response);
                    $("#fingers_modal").modal('show');
                },
                error: function(error) {
                    console.error("Error sending data to server:", error);
                }
            });
        }

        // Match the fingerprint
        function Check(isoTemplate, registration_id, session, className, section, roll) {
            var fingerprint = isoTemplate.isotemplate

            var res = MatchFinger(quality, timeout, fingerprint);
            if (res.httpStaus) {
                if (res.data.Status) {
                    $('#scanning').attr("src", "{{ url('assets/img/finger.png') }}");
                    // alert("Finger print axed matched");
                    fill_attendance(registration_id, session, className, section, roll, 'P' , 'Fingerprint');
                } else {
                    if (res.data.ErrorCode != "0") {
                        alert(res.data.ErrorDescription);
                    } else {
                        $('#scanning').attr("src", "{{ url('assets/img/finger.png') }}");
                        alert("Finger not matched");
                    }
                }
            } else {
                alert(res.err);
            }
        }

        // Get ISO Template
        function getIsotemplate(id, fingerImage) {

            $('.btn').attr('disabled', true);
            $('#scanning').attr("src", "{{ url('assets/img/scanning.gif') }}");

            setTimeout(function() {
                $.ajax({
                    url: "{{ route('fingers.show') }}",
                    type: "get",
                    data: {
                        id: id,
                        finger: fingerImage,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.isoTemplate) {
                            var student = response.student;
                            $('.btn').attr('disabled', false);
                            Check(response.isoTemplate, student.registration_id, student.session_id,
                                student.class_id, student.section_id, student.roll_no);
                        } else {
                            Check(null);
                        }
                    },
                    error: function(error) {
                        console.error("Error sending data to server:", error);
                    }
                });
            }, 1000)
        }
    </script>
@endsection
