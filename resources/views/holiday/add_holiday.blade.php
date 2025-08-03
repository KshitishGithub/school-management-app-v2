@extends('layouts.master')
@section('content')
    @push('title')
        <title>Add Holiday</title>
    @endpush
    @php
        define('PAGE', 'holiday_add');
    @endphp
    {{-- message --}}
    {{-- {!! Toastr::message() !!} --}}
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Holiday</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add Holiday</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">Add Holiday</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('holiday.list') }}" class="btn btn-primary">Back</a>
                                    </div>
                                </div>
                            </div>
                            <form id="holidayForm" method="post" action="{{route('holiday.store')}}">
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Holiday Name<span class="text-danger">*</span></label>
                                            <input type="text" name="holiday" class="form-control" placeholder="Enter Holiday">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Day<span class="text-danger">*</span></label>
                                            <input type="text" name="day" class="form-control" placeholder="Enter Day">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>Start Date<span class="text-danger">*</span></label>
                                            <input type="text" name="start_date" class="form-control datetimepicker" placeholder="Start date">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="form-group">
                                            <label>End Date<span class="text-danger">*</span></label>
                                            <input type="text" name="end_date" class="form-control datetimepicker" placeholder="End date">
                                        </div>
                                    </div>
                                    <div class="col-12">
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
        // Submit Form
        $("#holidayForm").submit(function(e) {
            e.preventDefault();
            SubmitForm("holidayForm", CallBack);

            function CallBack(result) {
                // console.log(result);
                var message = result.message;
                if (result.status == true) {
                    $("#holidayForm").trigger("reset");
                    toastr.success(message);
                } else {
                    toastr.error(message);
                }
            }
        });
    </script>
@endsection
