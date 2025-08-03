@extends('layouts.master')
@section('content')
    @push('title')
        <title>Add Class</title>
    @endpush
    @php
        define('PAGE', 'class_add');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Class</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add Class</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="addClassForm" method="POST" action="{{ route('class.store') }}">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="form-title"><span>Class Information</span></h5>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Class Name <span class="login-danger">*</span></label>
                                            <input type="text" name="class" class="form-control">
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
            // Submit Form
            $("#addClassForm").submit(function(e) {
                e.preventDefault();
                SubmitForm("addClassForm", CallBack);

                function CallBack(result) {
                    // console.log(result);
                    var message = result.message;
                    if (result.status) {
                        $("#addClassForm").trigger("reset");
                        toastr.success(message);
                    } else {
                        toastr.error(message);
                    }
                }
            });
        });
    </script>
@endsection
