@extends('setting.layouts.main')
@section('setting_content')
@push('title')
<title>Banner Add</title>
@endpush
@php
define('INNER_PAGE', 'banner');
define('PAGE_BREADCRUMB', 'Banner Setting');
@endphp

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Add Banner</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="{{ route('admin.setting.banner')}}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" id="bannerForm" action="{{ route('setting.banner.store') }}">
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
                                                    <input type="file" class="upload" name="bannerImage" id="imageInput">
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
                                                <textarea name="description" class="form-control" style="resize: none" cols="30" rows="5"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <div class="settings-btns">
                                        <button type="submit" class="btn btn-orange">Save</button>
                                        {{-- <a href="{{ route('admin.setting.banner') }}" class="btn btn-grey">Cancel</a> --}}
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
        $("#bannerForm").submit(function(e) {
            e.preventDefault();
            SubmitForm("bannerForm", CallBack);

            function CallBack(result) {
                // console.log(result);
                var message = result.message;
                if (result.status == true) {
                    $("#bannerForm").trigger("reset");
                    toastr.success(message);
                    $('#imagePreview').attr('src', '{{ url('assets/img/profiles/banner.png') }}');
                } else {
                    toastr.error(message);
                }
            }
        });
    </script>
@endsection
