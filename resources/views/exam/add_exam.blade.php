@extends('layouts.master')
@section('content')
    @push('title')
        <title>Add Exam</title>
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
                            <li class="breadcrumb-item active">Add Exam</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('exam.store') }}" id="ExamAddForm">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="form-title"><span>Exam Information</span></h5>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Exam Name <span class="text-danger">*</span></label>
                                            <input type="text" name="exam_name" class="form-control"
                                                placeholder="Enter exam name">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Class<span class="text-danger">*</span></label>
                                            <select class="form-control" name="class" id="selectedClass"
                                                aria-label="Default select example">
                                                <option selected value="">Choose class</option>
                                                @if ($classes->isNotEmpty())
                                                    @foreach ($classes as $class)
                                                        <option {{ Request::get('class') == $class->id ? 'selected' : '' }}
                                                            value="{{ $class->id }}">{{ $class->class }}
                                                        </option>
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
                                                <option selected value="">Choose Section</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Fees <span class="text-danger">*</span></label>
                                            <input type="number" name="fees" class="form-control" value="0"
                                                placeholder="Enter exam fees">
                                        </div>
                                    </div>
                                    <p>
                                    <h5>Subject details:</h5>
                                    </p>
                                    <table class="table table-bordered">
                                        <thead class="table-success">
                                            <tr>
                                                <th scope="col" class="text-center">Subject</th>
                                                <th scope="col" class="text-center">Exam Date</th>
                                                <th scope="col" class="text-center">Exam Day</th>
                                                <th scope="col" class="text-center">Start Time</th>
                                                <th scope="col" class="text-center">End Time</th>
                                                <th scope="col" class="text-center">Written marks</th>
                                                <th scope="col" class="text-center">Oral marks</th>
                                                <th scope="col" class="text-center">Pass marks</th>
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
                                                    </datalist>
                                                </td>
                                                <td><input type="date" name="exam_date[]" class="form-control"
                                                        optional="true" placeholder="Select date">
                                                </td>
                                                <td><input type="text" name="exam_day[]" class="form-control"
                                                        optional="true" placeholder="Enter exam day"></td>
                                                <td><input type="time" name="start_time[]" class="form-control"
                                                        optional="true"></td>
                                                <td><input type="time" name="end_time[]" class="form-control"
                                                        optional="true"></td>
                                                <td><input type="number" name="full_marks[]" class="form-control"
                                                        optional="true" placeholder="Enter full marks"></td>
                                                <td><input type="number" name="oral_marks[]" class="form-control"
                                                        optional="true" placeholder="Enter oral marks"></td>
                                                <td><input type="number" name="pass_marks[]" class="form-control"
                                                        optional="true" placeholder="Enter pass marks"></td>
                                                <td>
                                                    <select class="form-control" name="subject_type[]" optional="true">
                                                        <option value="" disabled selected>Select Subject Type</option>
                                                        <option value="Compulsory">Compulsory</option>
                                                        <option value="Optional">Optional</option>
                                                    </select>
                                                </td>
                                                <td class="NoPrint"><button type="button"
                                                        class="btn btn-sm btn-danger btn text-light"
                                                        onclick="BtnDel(this)">X</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="col-12 mt-3 text-center">
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
            $("#ExamAddForm").submit(function(e) {
                e.preventDefault();
                SubmitForm("ExamAddForm", CallBack);

                function CallBack(result) {
                    // console.log(result);
                    var message = result.message;
                    if (result.status) {
                        $("#ExamAddForm").trigger("reset");
                        window.location.reload();
                    } else {
                        toastr.error(message);
                    }
                }
            });
        });



        // Add the subject details column

        function BtnAdd() {
            /*Add Button*/
            var v = $("#TRow").clone().appendTo("#TBody");
            $(v).find("input").val('');
            $(v).removeClass("d-none");
            // $(v).find("input").attr("required", true);
            $(v).find("th").first().html($('#TBody tr').length - 1);
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
