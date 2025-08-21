@extends('setting.layouts.main')
@section('setting_content')
    @push('title')
        <title>
            Admin Settings</title>
    @endpush
    @php
        define('INNER_PAGE', 'general_setting');
        define('PAGE_BREADCRUMB', 'General Setting');
    @endphp
    @if (Auth::user()->role == '4')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">School Basic Details</h5>
                    </div>
                    <form action="{{ route('admin.setting.store') }}" method="post" id="settingForm">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="settings-form">
                                        <div class="form-group">
                                            <label>School Name <span class="star-red">*</span></label>
                                            <input type="text" class="form-control"
                                                value="{{ $settings->school_name ?? '' }}" name="school_name"
                                                placeholder="Enter School Name">
                                        </div>
                                        <div class="form-group">
                                            <label>Medium</label>
                                            <input type="text" class="form-control" optional="true"
                                                value="{{ $settings->medium ?? '' }}" name="medium"
                                                placeholder="Enter School Medium">
                                        </div>
                                        <div class="form-group">
                                            <p class="settings-label">Logo @if (empty($settings->logo))
                                                    <span class="star-red">*</span>
                                                @endif
                                            </p>
                                            <input type="file" accept="image/*" name="logo"
                                                @if (!empty($settings->logo)) optional="true" @endif id="imageInput"
                                                class="form-control">
                                            <div class="upload-images">
                                                @if (!empty($settings->logo))
                                                    <img id="imagePreview"
                                                        src="{{ asset("uploads/images/setting/$settings->logo") }}"
                                                        alt="Image">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <p class="settings-label">Favicon @if (empty($settings->favicon))
                                                    <span class="star-red">*</span>
                                                @endif
                                            </p>
                                            <input type="file" accept="image/*" name="favicon" id="imageInput2"
                                                @if (!empty($settings->favicon)) optional="true" @endif
                                                class="form-control">
                                            <h6 class="settings-size mt-1">Accepted formats: only png and ico</h6>
                                            <div class="upload-images upload-size">
                                                @if (!empty($settings->favicon))
                                                    <img id="imagePreview2"
                                                        src="{{ asset("uploads/images/setting/$settings->favicon") }}"
                                                        alt="Image">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="settings-form">
                                        <div class="form-group">
                                            <label>Registration</label>
                                            <input type="text" class="form-control" name="registration" optional="true"
                                                value="{{ $settings->registration ?? '' }}" placeholder="Enter Registration No">
                                        </div>
                                        <div class="form-group">
                                            <label>Village/City<span class="star-red">*</span></label>
                                            <input type="text" class="form-control" name="village"
                                                value="{{ $settings->village ?? '' }}" placeholder="Enter Village/City">
                                        </div>
                                        <div class="form-group">
                                            <label>Post Office <span class="star-red">*</span></label>
                                            <input type="text" class="form-control"
                                                value="{{ $settings->post_office ?? '' }}" name="post_office"
                                                placeholder="Enter Post Office">
                                        </div>
                                        <div class="form-group">
                                            <label>Police Station <span class="star-red">*</span></label>
                                            <input type="text" class="form-control"
                                                value="{{ $settings->police_station ?? '' }}" name="police_station"
                                                placeholder="Enter Police Station">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>District <span class="star-red">*</span></label>
                                                    <input type="text" value="{{ $settings->district ?? '' }}"
                                                        name="district" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Zip/Postal Code <span class="star-red">*</span></label>
                                                    <input type="text" name="pin_code"
                                                        value="{{ $settings->pin_code ?? '' }}" class="form-control"
                                                        data-inputmask='"mask": "999999"' data-mask>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>State <span class="star-red">*</span></label>
                                                    <input type="text" name="state"
                                                        value="{{ $settings->state ?? '' }}" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Country <span class="star-red">*</span></label>
                                                    <input type="text" name="country"
                                                        value="{{ $settings->country ?? '' }}" class="form-control"
                                                        value="Inida">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Contact <span class="star-red">*</span></label>
                                                    <input type="text" name="contact"
                                                        value="{{ $settings->contact ?? '' }}" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email <span class="star-red">*</span></label>
                                                    <input type="text" name="email"
                                                        value="{{ $settings->email ?? '' }}" class="form-control"
                                                        value="Inida">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{--  <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Firebase Token</label>
                                        <input type="text" value="{{ $settings->firebase_token ?? '' }}"
                                            name="firebase_token" class="form-control" optional='true'>
                                    </div>
                                </div>  --}}
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>One Signal Api Key</label>
                                        <input type="text" value="{{ $settings->one_signal_api_key ?? '' }}"
                                            name="one_signal_api_key" class="form-control" optional='true'>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>One Signal App ID</label>
                                        <input type="text" value="{{ $settings->one_signal_app_id ?? '' }}"
                                            name="one_signal_app_id" class="form-control" optional='true'>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Registration Prefix</label>
                                        <input type="text" value="{{ $settings->registration_prefix ?? '' }}"
                                            name="registration_prefix" class="form-control" optional='true'>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group mb-0">
                                <div class="settings-btns d-flex">
                                    <button type="submit" class="btn btn-orange">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('customJS')
    <script>
        // Submit Form
        $("#settingForm").submit(function(e) {
            e.preventDefault();
            SubmitForm("settingForm", CallBack);

            function CallBack(result) {
                // console.log(result);
                var message = result.message;
                if (result.status == true) {
                    $("#settingForm").trigger("reset");
                    toastr.success(message);
                    window.location.reload();
                    // $('#imagePreview').attr('src', '{{ url('assets/img/profiles/banner.png') }}');
                } else {
                    toastr.error(message);
                }
            }
        });

        // Favicon preview...........
        $('#imageInput2').on('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview2').attr('src', e.target.result);
                };
                reader.readAssettingsURL(file);
            }
        });
    </script>
@endsection
