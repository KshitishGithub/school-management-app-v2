@extends('layouts.master')
@section('content')
    @push('title')
        <title>Add Event</title>
    @endpush
    @php
        define('PAGE', 'event_add');
    @endphp
    {{-- message --}}
    {{-- {!! Toastr::message() !!} --}}
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Event</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add Event</li>
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
                                        <h3 class="page-title">Add Event</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('event.list') }}" class="btn btn-primary">Back</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <form method="POST" id="eventForm" action="{{ route('event.store') }}">
                                        <div class="bank-inner-details">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group">
                                                        <label>Title</label>
                                                        <input type="text" class="form-control" name="title">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="form-group">
                                                        <label>Banner Image</label>
                                                        <div class="change-photo-btn">
                                                            <div>
                                                                <p>Add Image <span class="text-danger">*</span></p>
                                                            </div>
                                                            <input type="file" class="upload" name="eventImage"
                                                                id="imageInput">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 mt-lg-3">
                                                    <div class="form-group">
                                                        <img class="img-fluid" id="imagePreview"
                                                            src="{{ url('assets/img/profiles/banner.png') }}"
                                                            alt="Image Preview" style="max-width: 300px">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12">
                                                    <div class="form-group">
                                                        <label>Description</label>
                                                        {{-- <input type="text" name="description" id="editor"> --}}
                                                        <textarea name="description" class="form-control" style="resize: none" cols="30" rows="5"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <div class="settings-btns">
                                                <button type="submit" class="btn btn-orange">Save</button>
                                                <a href="{{ route('event.list') }}" class="btn btn-grey">Cancel</a>
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
    </div>
@endsection
@section('customJS')
    <script>
        // Submit Form
        $("#eventForm").submit(function(e) {
            e.preventDefault();
            SubmitForm("eventForm", CallBack);

            function CallBack(result) {
                // console.log(result);
                var message = result.message;
                if (result.status == true) {
                    $("#eventForm").trigger("reset");
                    toastr.success(message);
                    $('#imagePreview').attr('src', '{{ url('assets/img/profiles/banner.png') }}');
                } else {
                    toastr.error(message);
                }
            }
        });
    </script>
@endsection
