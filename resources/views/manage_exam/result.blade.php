@extends('layouts.master')
@section('content')
    @push('title')
        <title>Result</title>
    @endpush
    @php
        define('PAGE', 'result');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Result</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Result</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="student-group-form">
                <form action="{{ route('exam.result') }}" method="get">
                    <div class="row">
                        <div class="col-lg-3 col-md-5">
                            <div class="form-group">
                                <select class="form-control" name="exams" id="selectedExam"
                                    aria-label="Default select example">
                                    <option selected value="">Choose exam</option>
                                    @if ($exams->isNotEmpty())
                                        @foreach ($exams as $exam)
                                            <option {{ Request::get('exams') == $exam->id ? 'selected' : '' }}
                                                value="{{ $exam->id }}">{{ $exam->exam_name }} - {{ $exam->class }}
                                                {{ $exam->section !== null ? - $exam->section : ''}}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-5">
                            <div class="form-group">
                                <select class="form-control" name="subject" id="subject" optional="true"
                                    aria-label="Default select example">
                                    @if (Request::get('subject'))
                                        <option selected value="">All subject</option>
                                        @foreach ($subjects as $subject)
                                            <option {{ Request::get('subject') == $subject->id ? 'selected' : '' }}
                                                value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                        @endforeach
                                    @else
                                        <option selected value="">All subject</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <div class="search-student-btn">
                                    <button type="btn" type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @include('layouts.message')
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                    </div>
                                </div>
                            </div>
                            @if ($studentResults)
                                @foreach ($studentResults as $student)
                                    <div class="card">
                                        <div class="card-body row bg-info text-light">
                                            <div class="col-md-5 fw-bolder">
                                                Name : {{ $student['name'] }}
                                            </div>
                                            <div class="col-md-3 fw-bolder">
                                                Class : {{ $student['class'] }}
                                            </div>
                                            <div class="col-md-2 fw-bolder">
                                                Section : {{ $student['section'] ?? 'N/A' }}
                                            </div>
                                            <div class="col-md-2 fw-bolder">
                                                Roll No : {{ $student['roll_no'] }}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <table border="1"
                                                class="table text-center star-student table-hover table-bordered table-center mb-0 table-striped">
                                                <thead class="student-thread bg-primary">
                                                    <tr>
                                                        <th width="5%">SL</th>
                                                        <th>Subject</th>
                                                        <th width="20%">Written Marks</th>
                                                        <th width="20%">Oral Marks</th>
                                                        <th width="20%">Pass Marks</th>
                                                        <th width="20%">Written Obtained</th>
                                                        <th width="20%">Oral Obtained</th>
                                                        <th width="20%">Total Marks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($student['subjects'] as $i => $subject)
                                                        <tr>
                                                            <td>{{ ++$i }}</td>
                                                            <td>{{ $subject['subject'] }}</td>
                                                            <td>{{ $subject['full_marks'] }}</td>
                                                            <td>{{ $subject['oral_marks'] }}</td>
                                                            <td>{{ $subject['pass_marks'] }}</td>
                                                            <td>{{ $subject['marks_obtained'] }}</td>
                                                            <td>{{ $subject['oral_marks_obtained'] }}</td>
                                                            <td>{{ $subject['total_marks'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            @elseif ($studentExamSubject)
                                <div class="table-responsive">
                                    <table id="DataList"
                                        class="table border-1 table-bordered star-student table-hover table-center mb-0 table-striped">
                                        <thead class="student-thread">
                                            <tr class="text-center">
                                                <th width="5%">SL</th>
                                                <th>Name</th>
                                                <th width="7%">Class</th>
                                                <th width="5%">Section</th>
                                                <th width="5%">Roll No</th>
                                                <th>Subject</th>
                                                <th width="7%">Written Marks</th>
                                                <th width="7%">Oral Marks</th>
                                                <th width="7%">Pass Marks</th>
                                                <th width="7%">Written obtained</th>
                                                <th width="7%">Oral obtained</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($studentExamSubject as $i => $student)
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $student->name }}</td>
                                                    <td>{{ $student->class }}</td>
                                                    <td>{{ $student->section ?? 'N/A' }}</td>
                                                    <td>{{ $student->roll_no }}</td>
                                                    <td>{{ $student->subject }}</td>
                                                    <td>{{ $student->full_marks }}</td>
                                                    <td>{{ $student->oral_marks }}</td>
                                                    <td>{{ $student->pass_marks }}</td>
                                                    <td>{{ $student->marks_obtained }}</td>
                                                    <td>{{ $student->oral_marks_obtained }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
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
            // Selected exam
            $('#selectedExam').on('change', function() {
                var exam_id = $(this).val();
                $.ajax({
                    url: "{{ route('getExamSubject') }}",
                    type: "get",
                    data: {
                        exam_id: exam_id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#subject').find('option').text('Loading...');
                    },
                    success: function(response) {
                        $('#subject').empty(); // Clear all existing options
                        $('#subject').append('<option value="">All subject</option>');
                        if (response.status) {
                            $('#subject').find('option').not(':first').remove();
                            $('.selectLabel').text('').append('*');
                            $.each(response["subject"], function(key, value) {
                                $('#subject').append(
                                    `<option value='${value.subject_id}'>${value.subject}</option>`
                                );
                            });
                            $('#subject').attr('optional', 'false');
                        } else {
                            $('.selectLabel').text('');
                            $('#subject').find('option').not(':first').remove();
                            $('#subject').attr('optional', 'true');
                        }
                    }
                });
            });
        });
    </script>
@endsection
