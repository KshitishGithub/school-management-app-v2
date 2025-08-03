@extends('layouts.master')
@section('content')
    @push('title')
        <title>Edit Exam</title>
    @endpush
    @php
        define('PAGE', 'exam_add');
    @endphp
    {{-- message --}}
    {{-- {!! Toastr::message() !!} --}}
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Exam</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Edit Exam</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('exam.update',$exams->id) }}" id="ExamUpdateForm">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="form-title"><span>Exam Information</span></h5>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Exam Name <span class="text-danger">*</span></label>
                                            <input type="text" name="exam_name" class="form-control"
                                                value="{{ $exams->exam_name }}" placeholder="Enter exam name">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Class<span class="text-danger">*</span></label>
                                            <select class="form-control" name="class" id="selectedClass"
                                                aria-label="Default select example">
                                                @if ($classes->isNotEmpty())
                                                    @foreach ($classes as $class)
                                                        @if ($exams->class == $class->id)
                                                            <option selected value="{{ $class->id }}">{{ $class->class }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $class->id }}">{{ $class->class }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Section <span class="text-danger sectionLabel"></span></label>
                                            <select class="form-control" name="section" id="section" optional="true"
                                                aria-label="Default select example">
                                                @if ($exams->section !== null)
                                                    <option value="{{ $exams->section }}">{{ $exams->section }}</option>
                                                @else
                                                    <option selected value="">Choose Section</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Fees <span class="text-danger">*</span></label>
                                            <input type="number" name="fees" class="form-control"
                                                value="{{ $exams->fees }}" placeholder="Enter exam fees">
                                        </div>
                                    </div>
                                    <p>
                                    <h5>Subject details:</h5>
                                    <small class="text-danger"><b>Note:</b>Any subject rows not visible in this table will be automatically deleted after you press the submit button, so please review carefully.</small>
                                    </p>
                                    <table class="table table-bordered">
                                        <thead class="table-success">
                                            <tr>
                                                <th scope="col" class="text-center">Subject</th>
                                                <th scope="col" class="text-center">Exam Date</th>
                                                <th scope="col" class="text-center">Exam Day</th>
                                                <th scope="col" class="text-center">Start Time</th>
                                                <th scope="col" class="text-center">End Time</th>
                                                <th scope="col" class="text-center">Written Marks</th>
                                                <th scope="col" class="text-center">Oral Marks</th>
                                                <th scope="col" class="text-center">Pass Marks</th>
                                                <th scope="col" class="text-center">Type</th>
                                                <th scope="col" width='5%' class="NoPrint">
                                                    <button type="button" class="btn btn-sm btn-success btn text-light"
                                                        onclick="BtnAdd()">+</button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="TBody">
                                            <tr id="TRow" class="d-none">
                                                <td>
                                                    <input type="text" class="form-control" list="subject"
                                                        name="subject[]" placeholder="Type or search" optional="true">
                                                    <datalist id="subject">
                                                        @foreach ($subjects as $subject)
                                                            <option value="{{ $subject->subject }}">{{ $subject->subject }}
                                                            </option>
                                                        @endforeach
                                                    </datalist>
                                                </td>
                                                <td>
                                                    <input type="date" name="exam_date[]" class="form-control" optional="true">
                                                </td>
                                                <td>
                                                    <input type="text" name="exam_day[]" class="form-control" optional="true"
                                                        placeholder="Enter exam day">
                                                </td>
                                                <td>
                                                    <input type="time" name="start_time[]" class="form-control" optional="true">
                                                </td>
                                                <td>
                                                    <input type="time" name="end_time[]" class="form-control" optional="true">
                                                </td>
                                                <td>
                                                    <input type="number" name="full_marks[]" class="form-control" optional="true"
                                                        placeholder="Enter full marks">
                                                </td>
                                                <td>
                                                    <input type="number" name="oral_marks[]" class="form-control" optional="true"
                                                        placeholder="Enter oral marks">
                                                </td>
                                                <td>
                                                    <input type="number" name="pass_marks[]" class="form-control" optional="true"
                                                        placeholder="Enter pass marks">
                                                </td>
                                                <td>
                                                    <select class="form-control" name="subject_type[]" optional="true">
                                                        <option value="" disabled>Select Subject Type</option>
                                                        <option value="1">Compulsory</option>
                                                        <option value="0">Optional</option>
                                                    </select>
                                                </td>
                                                <td class="NoPrint">
                                                    <button type="button" class="btn btn-sm btn-danger btn text-light"
                                                        onclick="BtnDel(this)">X</button>
                                                </td>
                                            </tr>

                                            @if ($examsSubject->isNotEmpty())
                                                @foreach ($examsSubject as $index => $subject)
                                                    <tr>
                                                        <td>
                                                            <input type="text" class="form-control" name="subject[]"
                                                                value="{{ $subject->subject }}"
                                                                placeholder="Enter subject name">
                                                        </td>
                                                        <td>
                                                            <input type="date" name="exam_date[]" class="form-control"
                                                                value="{{ $subject->exam_date }}">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="exam_day[]" class="form-control"
                                                                value="{{ $subject->exam_day }}">
                                                        </td>
                                                        <td>
                                                            <input type="time" name="start_time[]"
                                                                class="form-control" value="{{ $subject->start_time }}">
                                                        </td>
                                                        <td>
                                                            <input type="time" name="end_time[]" class="form-control"
                                                                value="{{ $subject->end_time }}">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="full_marks[]"
                                                                class="form-control" value="{{ $subject->full_marks }}"
                                                                placeholder="Enter full marks">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="oral_marks[]"
                                                                class="form-control" value="{{ $subject->oral_marks }}"
                                                                placeholder="Enter full marks">
                                                        </td>
                                                        <td>
                                                            <input type="number" name="pass_marks[]"
                                                                class="form-control" value="{{ $subject->pass_marks }}"
                                                                placeholder="Enter pass marks">
                                                        </td>
                                                        <td>
                                                            <select class="form-control" name="subject_type[]">
                                                                <option value="1"
                                                                    {{ $subject->subjectType == 1 ? 'selected' : '' }}>
                                                                    Compulsory</option>
                                                                <option value="0"
                                                                    {{ $subject->subjectType == 0 ? 'selected' : '' }}>
                                                                    Optional</option>
                                                            </select>
                                                        </td>
                                                        <td class="NoPrint">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger btn text-light"
                                                                onclick="BtnDel(this)">X</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="col-12 mt-3 text-center">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
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
                    url: "{{ route('getSectionandSubject') }}",
                    type: "get",
                    data: {
                        class_id: class_id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#section').find('option').text('Loading...');
                    },
                    success: function(response) {
                        // console.log(response[0].status);

                        // Section get and set after selecting the class

                        $('#section').find('option').text('Select section');
                        if (response[0].status) {
                            $('#section').find('option').not(':first').remove();
                            $('.sectionLabel').text('').append('*');
                            $.each(response[0]["sections"], function(key, value) {
                                $('#section').append(
                                    `<option value='${value.id}'>${value.section}</option>`
                                );
                            });
                            $('#section').attr('optional', 'false');
                        } else {
                            $('.sectionLabel').text('');
                            $('#section').find('option').not(':first').remove();
                            $('#section').attr('optional', 'true');
                        }

                        // // Subject
                        $('#subject').find('option').text('Select subject');
                        if (response[1].status) {
                            $('#subject').find('option').not(':first').remove();
                            $('.selectLabel').text('').append('*');
                            $.each(response[1]["subject"], function(key, value) {
                                $('#subject').append(
                                    `<option value='${value.subject}'>${value.subject}</option>`
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
            $("#ExamUpdateForm").submit(function(e) {
                e.preventDefault();
                SubmitForm("ExamUpdateForm", CallBack);

                function CallBack(result) {
                    // console.log(result);
                    var message = result.message;
                    if (result.error == false) {
                        window.location.href = "{{ route('exam.list') }}";
                    } else {
                        toastr.error(message);
                    }
                }
            });
        });



        // Add the subject details column

        // Add the subject details row dynamically
        function BtnAdd() {
            // Clone the hidden row template
            var v = $("#TRow").clone().appendTo("#TBody");

            // Make the cloned row visible
            $(v).removeClass("d-none");

            // Clear the input values in the new row
            $(v).find("input, select").val('');

            // Update the row number for each row
            $("#TBody").find("tr").each(function(index) {
                $(this).find("th").first().html(index + 1);
            });
        }

        // Remove the subject details column
        function BtnDel(v) {
            /*Delete Button*/
            $(v).parent().parent().remove();

            $("#TBody").find("tr").each(
                function(index) {
                    $(this).find("th").first().html(index);
                }

            );
        }
    </script>
@endsection
