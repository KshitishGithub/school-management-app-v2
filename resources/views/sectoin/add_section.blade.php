@extends('layouts.master')
@section('content')
    @push('title')
        <title>Add Section</title>
    @endpush
    @php
        define('PAGE', 'section_add');
    @endphp
    {{-- message --}}
    {{-- {!! Toastr::message() !!} --}}
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Section</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add Section</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    {{-- @include('layouts.message') --}}
                    <div class="card">
                        <div class="card-body">
                            <form id="sectionForm" method="post" action="{{route('section.store')}}">
                                <div class="row">
                                    <div class="col-12">
                                        <h5 class="form-title"><span>Add Section</span></h5>
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
                                            <label>Section <span class="login-danger">*</span></label>
                                            <input type="text" name="section" class="form-control">
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
            $("#sectionForm").submit(function(e) {
                e.preventDefault();

                SubmitForm("sectionForm", CallBack);

                function CallBack(result) {
                // console.log(result);
                var message = result.message;
                if (result.status == true) {
                    $("#sectionForm").trigger("reset");
                    toastr.success(message);
                } else {
                    toastr.error(message);
                }
            }
            });
        });
    </script>
@endsection
