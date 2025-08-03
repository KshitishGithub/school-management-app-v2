@extends('layouts.master')
@section('content')
@push('title')
<title>Add Books</title>
@endpush
@php
define('PAGE', 'library');
@endphp
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Add Books</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('library.index') }}">Library</a></li>
                        <li class="breadcrumb-item active">Add Books</li>
                    </ul>
                </div>
                <div class="col-auto text-end float-end ms-auto download-grp">
                    <a href="{{ route('library.create_type') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add
                        Type</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('library.store') }}" id="library_store" method="POST"
                            enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Book Information</span></h5>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Book Name <span class="login-danger">*</span></label>
                                        <input type="text" class="form-control" name="book_name">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Class <span class="login-danger">*</span></label>
                                        <select class="form-control" name="class" id="selectedClass">
                                            <option selected disabled>Select Class</option>
                                            @foreach ($classes as $row)
                                            <option value="{{ $row->id }}">{{ $row->class }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Subject <span class="login-danger">*</span></label>
                                        <select class="form-control" name="subject" id="subject">
                                            <option selected disabled>Select Subject</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Quantity <span class="login-danger">*</span></label>
                                        <input type="number" name="quantity" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Price <span class="login-danger">*</span></label>
                                        <input type="number" name="price" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Type <span class="login-danger">*</span></label>
                                        <select name="type" class="form-control">
                                            <option selected disabled>Select Type</option>
                                            @foreach ($book_type as $row)
                                            <option value="{{ $row->id }}">{{ $row->type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <div class="form-group local-forms">
                                        <label>Status <span class="login-danger">*</span></label>
                                        <select name="status" class="form-control">
                                            <option selected disabled>Select Status</option>
                                            <option value='1'>Active</option>
                                            <option value='0'>Deactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="student-submit">
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
        // Selected class
            $('#selectedClass').on('change', function() {
                var class_id = $(this).val();
                $.ajax({
                    url: "{{ route('getSubject') }}",
                    type: "get",
                    data: {
                        class_id: class_id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#subject').find('option').text('Loading...');
                    },
                    success: function(response) {
                        // subject get and sestatusCoder selecting the class
                        $('#subject').find('option').text('Select subject');
                        if (response.status) {
                            $('#subject').find('option').not(':first').remove();
                            $('.selectLabel').text('').append('*');
                            console.log(response.subjects);
                            $.each(response["subjects"], function(key, value) {
                                console.log(value.subject);
                                $('#subject').append(
                                    `<option value='${value.id}'>${value.subject}</option>`
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
            $("#library_store").submit(function(e) {
                e.preventDefault();
                SubmitForm("library_store", CallBack);

                function CallBack(result) {
                    // console.log(result);
                    var message = result.message;
                    if (result.status == true) {
                        $("#library_store").trigger("reset");
                        toastr.success(message);
                    } else {
                        toastr.error(message);
                    }
                }
            });
        });
</script>
@endsection
