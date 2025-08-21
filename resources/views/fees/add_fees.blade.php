@extends('layouts.master')
@section('content')
@push('title')
<title>Fees Collection</title>
@endpush
@php
define('PAGE', 'fees');
@endphp
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Add Fees</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('fees.list') }}">Fees Collect</a></li>
                        <li class="breadcrumb-item active">Add Fees</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form id="feesForm" method="post" action="{{ route('fees.store') }}">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Fees Information</span></h5>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Student ID</label>
                                        <input type="text"
                                            value="{{ config('website.registration') . $student['student_data']->id }}"
                                            readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Student Name</label>
                                        <input type="hidden" name="s_id" value="{{ $student['student_data']->id }}">
                                        <input type="hidden" id="class_id" name="class_id"
                                            value="{{ $student['student_data']->class_id }}">
                                        <input type="hidden" name="session_id"
                                            value="{{ $student['student_data']->session }}">
                                        <input type="text" name="name" value="{{ $student['student_data']->name }}"
                                            readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Gender</label>
                                        <input type="text" name="gender" id="gender" class="form-control"
                                            value="{{ $student['student_data']->gander }}" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-2">
                                        <div class="form-group local-forms">
                                            <label>Fees Type <span class="login-danger">*</span></label>
                                            <select class="form-control" id="fees_type">
                                                <option value="" disabled selected>Select Type</option>
                                                <option value="Admission Fees">Admission Fees</option>
                                                <option value="School Fees">School Fees</option>
                                                <option value="Exam Fees">Exam Fees</option>
                                                @if (count($student['options']) > 0)
                                                @foreach ($student['options'] as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-2 d-none" id="monthSelect">
                                        <div class="form-group local-forms">
                                            <label>Months <span class="login-danger" id="feesTypeWarning"
                                                    style="display: none;">*</span></label>
                                            <select class="form-control" id="month">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-2 d-none" id="examSelect">
                                        <div class="form-group local-forms">
                                            <label>Exams List<span class="login-danger" id="examTypeWarning"
                                                    style="display: none;">*</span></label>
                                            <select class="form-control" id="exam">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-2">
                                        <div class="form-group local-forms">
                                            <label>Fees Amount <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" id="amount" placeholder="Amount">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-2">
                                        <div class="form-group local-forms">
                                            <label>Fees Status <span class="login-danger">*</span></label>
                                            <select class="form-control" id="status">
                                                <option value="" selected>Select Status</option>
                                                <option value="Paid">Paid</option>
                                                <option value="Due">Due</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-2">
                                        <div class="form-group local-forms">
                                            <label>Remarks</label>
                                            <input class="form-control" type="text" id="remarks" placeholder="Remarks">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-1 add_btn">
                                        <button class="btn btn-success"><i class="fa fa-plus"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="table-responsive my-3">
                                    <table class="table table-bordered table-strip table-hover" id="feesTable">
                                        <thead>
                                            <tr class="text-center table-dark">
                                                <th>Fees Type</th>
                                                <th>Month/Exam</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <!-- New rows will be appended here -->
                                        </tbody>
                                        <tfoot>
                                            <tr class="text-center">
                                                <td colspan="5" class="text-end">Total</td>
                                                <td colspan="1" id="totalAmount">0</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="col-12">
                                    <div class="col-auto ms-auto float-end download-grp">
                                        <a href="{{ route('fees.list') }}" class="btn btn-primary">Cancel</a>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
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
            function calculateTotal() {
                var total = 0;
                $('#feesTable tbody tr').each(function() {
                    var amount = $(this).find('input[name="amount[]"]').val();
                    if (amount) {
                        total += parseFloat(amount);
                    }
                });
                $('#totalAmount').text(total.toFixed(2));
            }

            // Add more rows to the table
            $(document).on('click', '.add_btn', function(e) {
                e.preventDefault();

                // Extract form data
                var feesType = $('#fees_type').val();
                var feesTypeText = $('#fees_type option:selected').text();
                var month = $('#month').val() || 'N/A';
                var monthText = $('#month option:selected').text() || 'N/A';
                var exam = $('#exam').val() || 'N/A';
                var examText = $('#exam option:selected').text() || 'N/A';
                var amount = $('#amount').val();
                var status = $('#status').val();
                var remarks = $('#remarks').val();

                // Validate required fields
                if (!feesType || !amount || !status) {
                    alert('Please fill in all required fields.');
                    return;
                }

                // Append new row to the table
                var newRow = `
                    <tr>
                        <td><input type="hidden" name="fees_type[]" value="${feesType}">${feesTypeText}</td>
                        <td>${feesType === 'Exam Fees' ? '<input type="hidden" name="exam[]" value="'+exam+'">'+examText : '<input type="hidden" name="month[]" value="'+month+'">'+monthText}</td>
                        <td><input type="hidden" name="amount[]" value="${amount}">${amount}</td>
                        <td><input type="hidden" name="status[]" value="${status}">${status}</td>
                        <td><input type="hidden" name="remarks[]" value="${remarks}">${remarks}</td>
                        <td><i class="fa fa-trash text-danger remove_row" aria-hidden="true"></i></td>
                    </tr>
                `;
                $('#feesTable tbody').append(newRow);

                // Recalculate total amount
                calculateTotal();

                // Clear form inputs
                $('#fees_type').val('');
                $('#month').val('');
                $('#exam').val('');
                $('#amount').val('');
                $('#status').val('');
                $('#remarks').val('');

                // Hide and disable month and exam selects
                $('#monthSelect').addClass('d-none').removeClass('d-block');
                $('#examSelect').addClass('d-none').removeClass('d-block');
                $('#month').prop('disabled', true);
                $('#exam').prop('disabled', true);
                $('#feesTypeWarning, #examTypeWarning').css('display', 'none');
            });

            // Remove row from the table
            $(document).on('click', '.remove_row', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();

                // Recalculate total amount
                calculateTotal();
            });

            // Handle fees type change
            $(document).on('change', '#fees_type', function() {
                var selectedValue = $(this).val();
                var s_id = $('input[name="s_id"]').val();
                var class_id = $('#class_id').val();

                var data = {
                    s_id:s_id,
                    feesType:selectedValue,
                    class_id: class_id,
                }

                $.ajax({
                    url: '{{ route('getMonth') }}',
                    method: "POST",
                    data: data,
                    beforeSend: function() {
                        $('#overlayer').show();
                    },
                    success: function(response) {
                        $('#overlayer').hide();
                        if (response.status) {

                            if(response.type == 'month') {
                                $('#month').empty();
                                $('#amount').val('');
                                $('#month').append('<option value="" disabled selected>Select month</option>');
                                $.each(response.months, function(index, value) {
                                    $('#month').append('<option value="' + value + '">' + value + '</option>');
                                });
                            }else{
                                $('#exam').empty();
                                $('#amount').val('');
                                $('#exam').append('<option value="" disabled selected>Select exam</option>');

                                // Append options for each exam
                                $.each(response.exams, function(index, exam) {
                                    $('#exam').append('<option value="' + exam.id + '">' + exam.exam_name + '</option>');
                                });

                            }
                        } else {
                            alert("Error: Unable to fetch the price.");
                        }
                    },

                    error: function(xhr, status, error) {
                        console.error("Error submitting the form:", error);
                        console.error("Status submitting the form:", status);
                    }
                });

                if (["School Fees", "Transport Fees", "Hostel Fees", "Mess Fees"].includes(selectedValue)) {
                    $('#monthSelect').removeClass('d-none').addClass('d-block');
                    $('#examSelect').addClass('d-none').removeClass('d-block');
                    $('#month').prop('disabled', false);
                    $('#exam').prop('disabled', true);
                    $('#feesTypeWarning').css('display', 'inline');
                    $('#examTypeWarning').css('display', 'none');
                    $('#exam').val('');
                } else if (selectedValue === "Exam Fees") {
                    $('#monthSelect').addClass('d-none').removeClass('d-block');
                    $('#examSelect').removeClass('d-none').addClass('d-block');
                    $('#month').prop('disabled', true);
                    $('#exam').prop('disabled', false);
                    $('#feesTypeWarning').css('display', 'none');
                    $('#examTypeWarning').css('display', 'inline');
                    $('#month').val('');
                } else {
                    $('#month, #exam').prop('disabled', true);
                    $('#feesTypeWarning, #examTypeWarning').css('display', 'none');
                    $('#month, #exam').val('');
                }
            });

            {{--  Get the price according to the selected month  --}}
            $(document).on('change', '#month', function() {
                var class_id = $('#class_id').val();
                var feesType = $('#fees_type').val();
                var month = $(this).val();
                var s_id = $('input[name="s_id"]').val();

                if(feesType == ''){
                    alert('Please select a fee type');
                    return false;
                }
                var data = {
                    class_id:class_id,
                    feesType:feesType,
                    month : month,
                    s_id : s_id,
                }
                $.ajax({
                    url: '{{ route('getPrice') }}',
                    method: "POST",
                    data: data,
                    beforeSend: function() {
                        $('#overlayer').show();
                    },
                    success: function(response) {
                        $('#overlayer').hide();
                        if (response.status) {
                            var price = response.price;
                            if(price == null){
                                alert('No price found for the selected month');
                                return false;
                            }else{
                                {{--  set the price  --}}
                                $('#amount').val(price);
                            }
                        } else {
                            alert(response.message || "Error: Unable to fetch the price.");
                        }
                    },

                    error: function(xhr, status, error) {
                        console.error("Error submitting the form:", error);
                        console.error("Status submitting the form:", status);
                    }
                });
            });


            // get the exam fees according to the exam
            $(document).on('change', '#exam', function() {
                var class_id = $('#class_id').val();
                var exam = $(this).val();
                var s_id = $('input[name="s_id"]').val();
                var feesType = $('#fees_type').val();

                var data = {
                    class_id:class_id,
                    exam_id : exam,
                    s_id : s_id,
                    feesType : feesType,
                }
                $.ajax({
                    url: '{{ route('getExamPrice') }}',
                    method: "POST",
                    data: data,
                    beforeSend: function() {
                        $('#overlayer').show();
                    },
                    success: function(response) {
                        $('#overlayer').hide();
                        {{--  console.log(response);  --}}
                        if (response.status) {
                            var price = response.examFees;
                            $('#amount').val(price);
                        } else {
                            alert("Error: Unable to fetch the price.");
                        }
                    },

                    error: function(xhr, status, error) {
                        console.error("Error submitting the form:", error);
                        console.error("Status submitting the form:", status);
                    }
                });
            });


            // Submit form
            $("#feesForm").submit(function(e) {
                e.preventDefault();

                // Function to gather and prepare form data
                function gatherFormData() {
                    const formData = {
                        s_id: $('input[name="s_id"]').val(),
                        session_id: $('input[name="session_id"]').val(),
                        name: $('input[name="name"]').val(),
                        gender: $('input[name="gender"]').val(),
                        fees_type: [],
                        month: [],
                        amount: [],
                        status: [],
                        remarks: [],
                        exam: []
                    };

                    $('#feesTable tbody tr').each(function() {
                        const feesType = $(this).find('input[name="fees_type[]"]').val();
                        const month = $(this).find('input[name="month[]"]').val();
                        const exam = $(this).find('input[name="exam[]"]').val();
                        const amount = $(this).find('input[name="amount[]"]').val();
                        const status = $(this).find('input[name="status[]"]').val();
                        const remarks = $(this).find('input[name="remarks[]"]').val();

                        formData.fees_type.push(feesType);
                        formData.month.push(month || null); // Use null if month is not applicable
                        formData.exam.push(exam ? parseInt(exam, 10) :
                        null); // Convert to number or use null
                        formData.amount.push(amount);
                        formData.status.push(status);
                        formData.remarks.push(remarks ||
                        null); // Use null if remarks is not provided
                    });

                    // Convert formData to FormData object
                    const data = new FormData();
                    for (const key in formData) {
                        if (Array.isArray(formData[key])) {
                            formData[key].forEach(value => {
                                data.append(`${key}[]`, value);
                            });
                        } else {
                            data.append(key, formData[key]);
                        }
                    }

                    return data;
                }


                // Gather the form data
                const data = gatherFormData();
                // console.log(data);
                // Perform the AJAX request
                var form = $(this);
                $.ajax({
                    url: form.attr("action"),
                    method: form.attr("method"),
                    data: data,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#overlayer').show();
                    },
                    success: function(response) {
                        $('#overlayer').hide();
                        console.log(response);
                        if (response.status) {
                            toastr.success(response.message);
                            $('#feesTable tbody').html('');
                            calculateTotal();
                            {{--  window.location.href = response.url;  --}}
                            window.open(response.url, '_blank');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error submitting the form:", error);
                        console.error("Status submitting the form:", status);
                    }
                });
            });
        });
</script>
@endsection
