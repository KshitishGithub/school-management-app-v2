@extends('layouts.master')
@section('content')
    @push('title')
        <title>Add Marks</title>
    @endpush
    @php
        define('PAGE', 'add_marks');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Add Marks</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add Marks</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="student-group-form">
                <form action="{{ route('exam.index') }}" method="get">
                    <div class="row">
                        <div class="col-lg-3 col-md-5">
                            <div class="form-group">
                                <select class="form-control" name="exams" id="selectedExam"
                                    aria-label="Default select example">
                                    <option selected value="">Choose exam</option>
                                    @if ($exams->isNotEmpty())
                                        @foreach ($exams as $exam)
                                            <option {{ Request::get('exams') == $exam->id ? 'selected' : '' }}
                                                value="{{ $exam->id }}">{{ $exam->exam_name }} - {{ $exam->class }} - {{ $exam->section ?? 'N/A'}}
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
                                        @foreach ($subjects as $subject)
                                            <option {{ Request::get('subject') == $subject->id ? 'selected' : '' }}
                                                value="{{ $subject->id }}">{{ $subject->subject }}</option>
                                        @endforeach
                                    @else
                                        <option selected value="">Choose Subjects</option>
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
                    <form action="{{ route('exam.add_marks') }}" method="post" id="addMarks">
                        @csrf
                        <div class="card card-table">
                            <div class="card-body">
                                <div class="page-header">
                                    <div class="row align-items-center">
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            {{-- <a href="teachers.html" class="btn btn-outline-gray me-2 active"><i
                                                class="feather-list"></i></a>
                                        <a href="" class="btn btn-outline-gray me-2"><i class="feather-grid"></i></a>
                                        <a href="#" class="btn btn-outline-primary me-2"><i
                                                class="fas fa-download"></i> Download</a>
                                        <a href="" class="btn btn-primary"><i class="fas fa-plus"></i></a> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table id="DataList"
                                        class="table border-1 star-student table-hover table-center mb-0 table-striped">
                                        <thead class="student-thread">
                                            <tr class="text-center">
                                                {{--  <th width="7%">SL</th>  --}}
                                                <th>Name</th>
                                                <th width="7%">Class</th>
                                                <th width="5%">Section</th>
                                                <th width="5%">Roll No</th>
                                                <th>Exam name</th>
                                                <th>Subject</th>
                                                <th width="7%">Written Marks</th>
                                                <th width="7%">Written obtained</th>
                                                <th width="7%">Oral Marks</th>
                                                <th width="7%">Oral obtained</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($students as $i => $student)
                                                <tr>
                                                    <input type="hidden" name="registration_id[]"
                                                        value="{{ $student->registration_id }}">
                                                    <input type="hidden" name="session[]" value="{{ $student->session }}">
                                                    {{--  <td><input type="text" class="form-control text-center"
                                                            value="{{ ++$i }}" readonly></td>  --}}
                                                    <td><input type="text" class="form-control text-center"
                                                            name="name[]" value="{{ $student->name }}" readonly></td>
                                                    <td>
                                                        <input type="hidden" name="class[]"
                                                            value="{{ $student->class_id }}">
                                                        <input type="text" class="form-control text-center"
                                                             value="{{ $student->class }}" readonly></td>
                                                    <td>
                                                        <input type="hidden" name="section[]"
                                                        value="{{ $student->section_id ?? '0' }}">
                                                        <input type="text" class="form-control text-center"
                                                             value="{{ $student->section ?? 'N/A' }}"
                                                            readonly></td>
                                                    <td><input type="text" class="form-control text-center"
                                                            name="roll_no[]" value="{{ $student->roll_no }}" readonly></td>
                                                    <td>
                                                        <input type="hidden" name="exam_name[]"
                                                            value="{{ $student->exam_id }}">
                                                        <input type="text" class="form-control text-center"
                                                            value="{{ $student->exam_name }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="subject[]" value="{{ $student->id }}">
                                                        <input type="text" class="form-control text-center"
                                                            value="{{ $student->subject }}" readonly>
                                                    </td>
                                                    <td><input type="text" class="form-control text-center"
                                                            name="full_marks[]" value="{{ $student->full_marks }}"
                                                            readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="{{ $student->marks_obtained }}"
                                                        name="marks_obtained[]" class="form-control">
                                                    </td>
                                                    <td><input type="text" class="form-control text-center"
                                                        name="oral_marks[]" value="{{ $student->oral_marks }}"
                                                        readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="{{ $student->oral_marks_obtained }}"
                                                        name="oral_marks_obtained[]" class="form-control">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <div class="form-group">
                                <div class="search-student-btn">
                                    <button type="btn" type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
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
                        // subject get and set after selecting the class
                        $('#subject').find('option').text('Select subject');
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



            // Submit Form
            $("#addMarks").submit(function(e) {
                e.preventDefault();
                SubmitForm("addMarks", CallBack);

                function CallBack(result) {
                    console.log(result);
                    var message = result.message;
                    if (result.status) {
                        $("#addMarks").trigger("reset");
                        // toastr.success(message);
                        window.location.reload();
                    } else {
                        toastr.error(message);
                    }
                }
            });
        });
    </script>
@endsection
