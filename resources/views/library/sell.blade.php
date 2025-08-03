@extends('layouts.master')
@section('content')
@push('title')
<title>Sell</title>
@endpush
@php
define('PAGE', 'sell');
@endphp
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Library</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Sell</li>
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
                                    <h3 class="page-title">Library</h3>
                                </div>
                            </div>
                        </div>
                        <div class="student-group-form">
                            <form id="salesForm" action="{{ route('library.store_sale') }}" method="post">
                                <div class="row">
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group">
                                            <select class="form-control" name="class" id="selectedClass"
                                                aria-label="Default select example">
                                                <option selected disabled value="">Select Class</option>
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
                                    <div class="col-lg-2 col-md-6">
                                        <div class="form-group">
                                            <select class="form-control" name="registration" id="student" aria-label="Default select">
                                                {{--  @if (Request::get('student'))
                                                @foreach ($students as $student)
                                                <option {{ Request::get('student')==$student->id ? 'selected' : '' }}
                                                    value="{{ $student->id }}">
                                                    {{ $student->student }}
                                                </option>
                                                @endforeach
                                                @else  --}}
                                                <option selected disabled value="">Choose Student</option>
                                                {{--  @endif  --}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="invoice-add-table">
                                    <h4>Book Details</h4>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-success">
                                                <tr>
                                                    <th scope="col" class="text-center">Subject</th>
                                                    <th scope="col" class="text-center">Books</th>
                                                    <th scope="col" class="text-center">Quantity</th>
                                                    <th scope="col" class="text-center">Price</th>
                                                    <th scope="col" class="text-center">Total</th>
                                                    <th scope="col" width='5%' class="NoPrint">
                                                        <button type="button"
                                                            class="btn btn-sm btn-success btn text-light add-btn">+</button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="TBody">
                                                <tr id="TRow" class="d-none">
                                                    <td>
                                                        <select class="form-control subject" name="subject[]" aria-label="Default select example" optional=true>
                                                            <option selected disabled value="">Select Subject</option>
                                                            @if (Request::get('subject'))
                                                            @foreach ($subjects as $subject)
                                                            <option {{ Request::get('subject')==$subject->id ?
                                                                'selected' : '' }}
                                                                value="{{ $subject->id }}">
                                                                {{ $subject->subject }}
                                                            </option>
                                                            @endforeach
                                                            @else
                                                            <option selected value="">Choose subject</option>
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control book" name="book[]" optional=true
                                                            aria-label="Default select example">
                                                            <option selected value="">Choose book</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="quantity[]" class="form-control" optional=true>
                                                        <div class="qnt"></div>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="price[]" optional=true
                                                            class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="total[]" optional=true
                                                            class="form-control">
                                                    </td>
                                                    <td class="NoPrint">
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger btn text-light remove-btn">X</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" class="text-right"><strong>Total Amount:</strong>
                                                    </td>
                                                    <td id="totalAmountDisplay" colspan="2" class="text-center">0.00
                                                    </td>
                                                </tr>
                                            </tfoot>

                                        </table>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <div class="col-lg-2">
                                        <div class="search-student-btn">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="search-student-btn">
                                            <a href="{{ route('library.sell') }}" class="btn btn-primary">Reset</a>
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
</div>
@endsection
@section('customJS')
<script>
    $(document).ready(function() {
        // Selected class
        $('#selectedClass').on('change', function() {
            var class_id = $(this).val();
            $.ajax({
                url: "{{ route('getStudents') }}",
                type: "get",
                data: { class_id: class_id },
                dataType: "json",
                beforeSend: function() {
                    $('#student').find('option').text('Loading...');
                },
                success: function(response) {
                    {{--  console.log(response);  --}}
                    $('#student').find('option').text('Select student');
                    $('#subject').find('option').text('Select subject');
                    if (response.status) {
                        $('#student').find('option').not(':first').remove();
                        $.each(response["students"], function(key, value) {
                            $('#student').append(`<option value='${value.registration_id}'>${value.name}</option>`);
                        });
                        $('.subject').find('option').not(':first').remove();
                        $.each(response["subjects"], function(key, value) {
                            $('.subject').append(`<option value='${value.id}'>${value.subject}</option>`);
                        });
                    } else {
                        $('#student').find('option').not(':first').remove();
                        $('#student').attr('optional', 'true');
                        $('.subject').find('option').not(':first').remove();
                        $('.subject').attr('optional', 'true');
                    }
                }
            });
        });

        // Add more row in table
        $(document).on('click', '.add-btn', function() {
            var v = $("#TRow").clone().appendTo("#TBody");
            $(v).find("input").val('');
            $(v).removeClass("d-none");
            $(v).find("th").first().html($('#TBody tr').length - 1);
        });

        // Remove the subject details column
        $(document).on('click', '.remove-btn', function() {
            $(this).closest('tr').remove();
            calculateTotalAmount();
            $("#TBody").find("tr").each(function(index) {
                $(this).find("th").first().html(index + 1); // Update row index
            });
        });

        // Change the Book Dropdown when Subject Changes
        $(document).on('change', '.subject', function() {
            var subject_id = $(this).val();
            var currentRow = $(this).closest('tr');
            if (subject_id) {
                $.ajax({
                    url: "{{ route('getBooks') }}",
                    type: "get",
                    data: { subject_id: subject_id },
                    dataType: "json",
                    beforeSend: function() {
                        currentRow.find('.book').find('option').text('Loading...');
                    },
                    success: function(response) {
                        var bookDropdown = currentRow.find('.book');
                        if (response.status) {
                            bookDropdown.find('option').text('Select book');
                            bookDropdown.find('option').not(':first').remove();
                            $.each(response.books, function(key, value) {
                                bookDropdown.append(`<option value='${value.id}'>${value.book_name}</option>`);
                            });
                        } else {
                            bookDropdown.find('option').not(':first').remove();
                            bookDropdown.append(`<option value="">No books available</option>`);
                        }
                    },
                    error: function() {
                        alert('Failed to fetch books. Please try again.');
                    }
                });
            } else {
                currentRow.find('.book').find('option').not(':first').remove();
                currentRow.find('.book').append(`<option value="">Choose book</option>`);
            }
        });

        // Change the Book Dropdown when book Changes
        $(document).on('change', '.book', function() {
            var book_id = $(this).val();
            var currentRow = $(this).closest('tr');
            if (book_id) {
                $.ajax({
                    url: "{{ route('getBooksDetails') }}",
                    type: "get",
                    data: { book_id: book_id },
                    dataType: "json",
                    success: function(response) {
                        if (response.status && response.booksDetails.length > 0) {
                            var bookDetails = response.booksDetails[0];
                            var price = bookDetails.price;
                            var availableQuantity = bookDetails.quantity;
                            currentRow.find('input[name="price[]"]').val(price);
                            currentRow.find('.qnt').html(`<small class="text-danger">Quantity should be less than <span class='netQnt'>${availableQuantity}</span></small>`);
                            var quantity = currentRow.find('input[name="quantity[]"]').val();
                            if (quantity) {
                                var total = quantity * price;
                                currentRow.find('input[name="total[]"]').val(total);
                                calculateTotalAmount();
                            }
                        } else {
                            alert('Book details not found.');
                        }
                    },
                    error: function() {
                        alert('Failed to fetch book details. Please try again.');
                    }
                });
            } else {
                currentRow.find('.book').find('option').not(':first').remove();
                currentRow.find('.book').append(`<option value="">Choose book</option>`);
            }
        });

       // Row-wise total
        $(document).on('input', 'input[name="quantity[]"]', function () {
            var currentRow = $(this).closest('tr');
            var quantity = parseInt($(this).val()); // Convert to integer
            var totalQnt = parseInt(currentRow.find('.netQnt').text()); // Convert to integer

            if (quantity > totalQnt) {
                alert(`Quantity should be less than or equal to ${totalQnt}`);
                $(this).val(''); // Reset the input value to blank or 0
                return; // Exit the function
            }

            calculateRowTotal(currentRow);
            calculateTotalAmount();
        });

        function calculateTotalAmount() {
            var totalAmount = 0;
            $('tr').each(function() {
                var currentRow = $(this);
                var price = parseFloat(currentRow.find('input[name="price[]"]').val());
                var quantity = parseInt(currentRow.find('input[name="quantity[]"]').val());

                if (!isNaN(price) && !isNaN(quantity)) {
                    totalAmount += price * quantity;
                }
            });
            $('#totalAmountDisplay').text(totalAmount.toFixed(2));
            return totalAmount;
        }

        function calculateRowTotal(currentRow) {
            var price = parseFloat(currentRow.find('input[name="price[]"]').val());
            var quantity = parseInt(currentRow.find('input[name="quantity[]"]').val());
            var rowTotal = 0;

            if (!isNaN(price) && !isNaN(quantity)) {
                rowTotal = price * quantity;
            }

            currentRow.find('input[name="total[]"]').val(rowTotal.toFixed(2));
        }

        // Submit Form
        $("#salesForm").submit(function(e) {
            e.preventDefault();
            SubmitForm("salesForm", CallBack);

            function CallBack(result) {
                {{--  console.log(result);  --}}
                var message = result.message;
                if (result.status == true) {
                    $("#salesForm").trigger("reset");
                    {{--  toastr.success(message);  --}}
                    window.location.reload();
                } else {
                    toastr.error(message);
                }
            }
        });
    });
</script>


@endsection
