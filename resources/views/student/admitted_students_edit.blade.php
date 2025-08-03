@extends('layouts.master')
@section('content')
    @push('title')
        <title>Update Student</title>
    @endpush
    @php
        define('PAGE', 'students');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Edit Students</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                <li class="breadcrumb-item active">Edit Students</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title">Edit Form</h5>
                        <p><span class="text-danger">*</span> Indicates mandatory fields.</p>
                    </div>
                    <div class="card-body">
                        <form id="registrationFormUpdate" method="post"
                            action="{{ route('registered.updateAfterAdmission', ['id' => $student->id]) }}">
                            <div class="row">
                                <div class="col-sm-1">
                                    <label class="col-form-label">Session</label>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="session" id="session"
                                        value="{{ $session }}" readonly>
                                </div>
                            </div>
                            <hr>
                            <h5 class="card-title mt-3">Personal Details</h5>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Name<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="name" id="name"
                                        value="{{ $student->name }}">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label ">Date of Birth<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control datetimepicker " name="dob"
                                        value="{{ $student->dateOfBirth }}" placeholder="DD-MM-YYYY">
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Father's Name<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="fathers_name" id="fathers_name"
                                        value="{{ $student->fathersName }}">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Qualification</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" optional="true" name="fathers_qualification"
                                        value="{{ $student->fathersQualification }}" id="fathers_qualification">
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Occupation</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" optional="true" name="fathers_occupation"
                                        value="{{ $student->fathersOccupation }}" id="fathers_occupation">
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Mother's Name<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="mothers_name" id="mothers_name"
                                        value="{{ $student->mothersName }}">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Qualification</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" optional="true" name="mothers_qualification"
                                        id="mothers_qualification" value="{{ $student->mothersQualification }}">
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Occupation</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" optional="true" name="mothers_occupation"
                                        value="{{ $student->mothersOccupation }}" id="mothers_occupation">
                                </div>
                            </div>
                            <hr>
                            <h5 class="card-title mt-3">Others Details</h5>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Parents Mobile No<span class="text-danger">
                                            *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="mobile" id="mobile"
                                        value="{{ $student->mobile }}" data-inputmask='"mask": "9999999999"' data-mask>
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">WhatsApp No</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" optional="true" name="whatsapp_no"
                                        value="{{ $student->whatsapp }}" id="whatsapp_no"
                                        data-inputmask='"mask": "9999999999"' data-mask>
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Nationality</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="nationality" id="nationality"
                                        value="{{ $student->nationality }}" value="Indian" readonly>
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Religion<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input class="form-control" list="religion" name="religion"
                                        value="{{ $student->religion }}" placeholder="Type to search...">
                                    <datalist id="religion">
                                        <option value="Hindu">Hindu</option>
                                        <option value="Muslim">Muslim</option>
                                        <option value="Christian">Christian</option>
                                        <option value="Sikh">Sikh</option>
                                        <option value="Buddhist">Buddhist</option>
                                        <option value="Jain">Jain</option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Caste<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input class="form-control" list="caste" name="caste"
                                        value="{{ $student->caste }}" placeholder="Type to search...">
                                    <datalist id="caste">
                                        <option value="SC">SC</option>
                                        <option value="ST">ST</option>
                                        <option value="OBC">OBC</option>
                                        <option value="GEN">GEN</option>
                                    </datalist>
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Gander<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input class="form-control" list="gander" name="gander"
                                        value="{{ $student->gander }}" placeholder="Type to search...">
                                    <datalist id="gander">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Others">Others</option>
                                    </datalist>
                                </div>
                            </div>
                            <hr>
                            <h5 class="card-title mt-3">Postal Address</h5>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Village<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="village" id="village"
                                        value="{{ $student->village }}">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Post Office<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="post_office" id="post_office"
                                        value="{{ $student->postOffice }}">
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Police Station<span class="text-danger">
                                            *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="police_station"
                                        value="{{ $student->policeStation }}" id="police_station">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">District<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="district" id="district"
                                        value="{{ $student->district }}">
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">PIN<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="pin" id="pin"
                                        value="{{ $student->pin }}" data-inputmask='"mask": "999999"' data-mask>
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Aadhar<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="aadhar" id="aadhar" optional="true"
                                        value="{{ $student->aadhar }}" data-inputmask='"mask": "9999 9999 9999"'
                                        data-mask>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group mb-0">
                                        <label class="custom_check w-100">
                                            <input type="checkbox" id="transport" name="transport" optional="true"
                                                {{ $student->transport == 'Yes' ? 'checked' : '' }} value="Yes">
                                            <span class="checkmark"></span> Transport facility.
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" id="hostel" name="hostel" optional="true"
                                                {{ $student->hostel == 'Yes' ? 'checked' : '' }} value="Yes">
                                            <span class="checkmark"></span> Hostel facility.
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" id="mess" name="mess" optional="true"
                                                {{ $student->mess == 'Yes' ? 'checked' : '' }} value="Yes">
                                            <span class="checkmark"></span> Mess facility.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-10 row {{ $student->transport == 'Yes' ? '' : 'd-none' }}"
                                    id="transportDetails">
                                    <div class="col-sm-5 mt-md-4">
                                        <select class="form-control" name="route" id="selectedRoute" optional="true"
                                            aria-label="Default select example">
                                            <option selected value="">Choose Route</option>
                                            @if ($routes->isNotEmpty())
                                                @foreach ($routes as $route)
                                                    @if ($route->id == $student->route)
                                                        <option selected value="{{ $route->id }}">{{ $route->route }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $route->id }}">{{ $route->route }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-sm-5 mt-md-4">
                                        <select class="form-control" name="bus_stops" id="bus_stops" optional="true"
                                            aria-label="Default select example">
                                            <option selected value="">Choose Stops</option>
                                            @if ($bus_stops->isNotEmpty())
                                                @foreach ($bus_stops as $bus_stop)
                                                    @if ($bus_stop->id == $student->stops)
                                                        <option selected value="{{ $bus_stop->id }}">{{ $bus_stop->bus_stops }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $bus_stop->id }}">{{ $bus_stop->bus_stops }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <h5 class="card-title mt-3">Upload Photo</h5>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Photo<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="file" class="form-control" name="photo" id="imageInput"
                                        {{ !empty($student->photo) ? 'optional=true' : 'optional=false' }}
                                        accept="image/*">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Preview</label>
                                </div>
                                <div class="col-sm-4">
                                    <img class="img-fluid" id="imagePreview"
                                        src="{{ asset("uploads/images/registration/$student->photo") }}"
                                        alt="Image Preview" style="max-width: 100px">
                                </div>
                            </div>
                            <hr class="mt-4">
                            <div class="justify-content-end">
                                <div class="student-submit">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
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
                    url: "{{ route('getSection') }}",
                    type: "get",
                    data: {
                        class_id: class_id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#section').find('option').text('Loading...');
                    },
                    success: function(response) {
                        // Section get and set after selecting the class
                        $('#section').find('option').text('Select section');
                        if (response.status) {
                            $('#section').find('option').not(':first').remove();
                            $('.selectLabel').text('').append('*');
                            $.each(response["sections"], function(key, value) {
                                $('#section').append(
                                    `<option value='${value.id}'>${value.section}</option>`
                                );
                            });
                            $('#section').attr('optional', 'false');
                        } else {
                            $('.selectLabel').text('');
                            $('#section').find('option').not(':first').remove();
                            $('#section').attr('optional', 'true');
                        }
                    }
                });
            });

            // Selected Route
            $('#selectedRoute').on('change', function() {
                var route_id = $(this).val();
                $.ajax({
                    url: "{{ route('getStops') }}",
                    type: "get",
                    data: {
                        route_id: route_id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#bus_stops').find('option').text('Loading...');
                    },
                    success: function(response) {
                        // bus_stops get and set after selecting the class
                        $('#bus_stops').find('option').text('Select Stops');
                        if (response.status) {
                            $('#bus_stops').find('option').not(':first').remove();
                            $.each(response["busStops"], function(key, value) {
                                $('#bus_stops').append(
                                    `<option value='${value.id}'>${value.bus_stops}</option>`
                                );
                            });
                        } else {
                            $('#bus_stops').find('option').not(':first').remove();
                        }
                    }
                });
            });

            // Check Transport checkbox
            $('#transport').on('click', function() {
                if ($('#transport').is(':checked')) {
                    $('#selectedRoute').attr('optional', 'false');
                    $('#bus_stops').attr('optional', 'false');
                    $('#transportDetails').removeClass('d-none').addClass('d-block');
                } else {
                    $('#selectedRoute').attr('optional', 'true');
                    $('#bus_stops').attr('optional', 'true');
                    $('#transportDetails').removeClass('d-block').addClass('d-none');
                }
            })



            // Submit Form
            $("#registrationFormUpdate").submit(function(e) {
                e.preventDefault();
                SubmitForm("registrationFormUpdate", CallBack);

                function CallBack(result) {
                    console.log(result);
                    var message = result.message;
                    if (result.status) {
                        swal("Good job!", message, "success")
                            .then((value) => {
                                window.location.href = "{{ route('students.list') }}";
                            })
                    } else {
                        toastr.error(message);
                    }
                }
            });
        });
    </script>
@endsection
