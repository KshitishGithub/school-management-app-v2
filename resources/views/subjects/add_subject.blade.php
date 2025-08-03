@extends('layouts.master')
@section('content')
    @push('title')
        <title>Add Subjects</title>
    @endpush
    @php
        define('PAGE', 'subject_add');
    @endphp
    {{-- message --}}
    {{-- {!! Toastr::message() !!} --}}
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Subjects</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add Subjects</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="subjectForm" method="post" action="{{route('subject.store')}}">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="form-title"><span>Add Subjects</span></h5>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Class<span class="login-danger">*</span></label>
                                            {{-- <input type="text" class="form-control"> --}}
                                            <select name="class_id" id="class" class="form-control">
                                                <option value="" selected>Select class</option>
                                                @if ($classes->isNotEmpty())
                                                    {
                                                    @foreach ($classes as $class)
                                                        <option value="{{ $class->id }}">{{ $class->class }}</option>
                                                    @endforeach
                                                    }
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Subject <span class="login-danger">*</span></label>
                                            <input type="text" name="subject" class="form-control">
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
            $("#subjectForm").submit(function(e) {
                e.preventDefault();

                SubmitForm("subjectForm", CallBack);

                function CallBack(result) {
                // console.log(result);
                var message = result.message;
                if (result.status == true) {
                    $("#subjectForm").trigger("reset");
                    toastr.success(message);
                } else {
                    toastr.error(message);
                }
            }
            });
        });
    </script>
@endsection
