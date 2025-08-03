@extends('layouts.master')
@section('content')
    @push('title')
        <title>Registration</title>
    @endpush
    @php
        define('PAGE', 'registration');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Registration Students</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                <li class="breadcrumb-item active">Registration Students</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            {{-- message --}}
            {{-- {!! Toastr::message() !!} --}}
            <div class="row">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title">Registration Form</h5>
                        <p><span class="text-danger">*</span> Indicates mandatory fields.</p>
                    </div>
                    <div class="card-body">
                        <form id="registrationForm" method="post" action="{{ route('registration.store') }}">
                            <div class="row">
                                <div class="col-sm-1">
                                    <label class="col-form-label">Session</label>
                                </div>
                                <div class="col-sm-3">
                                    {{-- <input type="text" class="form-control" name="session" id="session" value="{{ $session }}" readonly> --}}
                                    <select name="session" class="form-control" id="session">
                                        <option selected value="{{ $session['id'] }}">{{ $session['session'] }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <label class="col-form-label">Class<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-3">
                                    <select class="form-control" name="class" id="selectedClass"
                                        aria-label="Default select example">
                                        <option selected value="">Choose class</option>
                                        @if ($classes->isNotEmpty())
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->class }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <label class="col-form-label">Section <span
                                            class="text-danger selectLabel"></span></label>
                                </div>
                                <div class="col-sm-3">
                                    <select class="form-control" name="section" id="section" optional="true"
                                        aria-label="Default select example">
                                        <option selected value="">Choose Section</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <h5 class="card-title mt-3">Personal Details</h5>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Name<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="name" id="name">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label ">Date of Birth<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control datetimepicker " id="dob"
                                        onchange="calculateAge()" name="dob" placeholder="DD-MM-YYYY" value=""
                                        style="">
                                    <div id="ageResult"></div>
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Fathers Name<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="fathers_name" id="fathers_name">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Qualification</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" optional="true" name="fathers_qualification"
                                        id="fathers_qualification">
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Occupation</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" optional="true" name="fathers_occupation"
                                        id="fathers_occupation">
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Mothers Name<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="mothers_name" id="mothers_name">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Qualification</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" optional="true"
                                        name="mothers_qualification" id="mothers_qualification">
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Occupation</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" optional="true" name="mothers_occupation"
                                        id="mothers_occupation">
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
                                        data-inputmask='"mask": "9999999999"' data-mask>
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">WhatsApp No</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" optional="true" name="whatsapp_no"
                                        id="whatsapp_no" data-inputmask='"mask": "9999999999"' data-mask>
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Nationality</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="nationality" id="nationality"
                                        value="Indian" readonly>
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Religion<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" list="religion" name="religion"
                                        placeholder="Type or search">
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
                                    <input type="text" class="form-control" list="caste" name="caste"
                                        placeholder="Type or search"> <datalist id="caste">
                                        <option value="SC">SC</option>
                                        <option value="ST">ST</option>
                                        <option value="OBC">OBC</option>
                                        <option value="GEN">GEN</option>
                                    </datalist>
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Gender<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" list="gender" name="gander"
                                        placeholder="Type or search">
                                    <datalist id="gender">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Others">Others</option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Blood Group</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" list="blood_group" name="blood_group"
                                        placeholder="Type or search">
                                    <datalist id="blood_group">
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="Unknown">Unknown</option>
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
                                    <input type="text" class="form-control" name="village" id="village">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Post Office<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="post_office" id="post_office">
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Police Station<span class="text-danger">
                                            *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="police_station"
                                        id="police_station">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">District<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="district" id="district">
                                </div>
                            </div>
                            <div class="row mt-md-4">
                                <div class="col-sm-2">
                                    <label class="col-form-label">PIN<span class="text-danger"> *</span></label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="pin" id="pin"
                                        data-inputmask='"mask": "999999"' data-mask>
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Aadhar</label>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" name="aadhar" id="aadhar" optional="true"
                                        data-inputmask='"mask": "9999 9999 9999"' data-mask>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-2 ">
                                    <div class="form-group mb-0">
                                        <label class="custom_check w-100">
                                            <input type="checkbox" id="transport" optional="true" name="transport" value="Yes">
                                            <span class="checkmark"></span> Transport facility.
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" id="hostel" optional="true" name="hostel" value="Yes">
                                            <span class="checkmark"></span> Hostel facility.
                                        </label>
                                        <label class="custom_check w-100">
                                            <input type="checkbox" id="mess" optional="true" name="mess" value="Yes">
                                            <span class="checkmark"></span> Mess facility.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-10 row d-none" id="transportDetails">
                                    <div class="col-sm-5">
                                        <select class="form-control" name="route" id="selectedRoute" optional="true"
                                            aria-label="Default select example">
                                            <option selected value="">Choose Route</option>
                                            @if ($routes->isNotEmpty())
                                                @foreach ($routes as $route)
                                                    <option value="{{ $route->id }}">{{ $route->route }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-sm-5 mt-md-3">
                                        <select class="form-control" name="bus_stops" id="bus_stops" optional="true"
                                            aria-label="Default select example">
                                            <option selected value="">Choose Stops</option>
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
                                        accept="image/*">
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Preview</label>
                                </div>
                                <div class="col-sm-4">
                                    <img class="img-fluid" id="imagePreview"
                                        src="{{ url('assets/img/profiles/demo.png') }}" alt="Image Preview"
                                        style="max-width: 100px">
                                </div>
                            </div>
                            <hr class="mt-4">
                            <div class="justify-content-end">
                                <div class="student-submit">
                                    <button type="submit" class="btn btn-primary">Submit</button>
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
            // Date calculate.....

            // function calculateAge() {
            //     alert("ok");
            //     const dobInput = document.getElementById('dob');
            //     const ageResultContainer = document.getElementById('ageResult');

            //     const dobValue = dobInput.value; // Get the value from the input field (format: DD-MM-YYYY)

            //     // Split the input value to extract day, month, and year
            //     const dobParts = dobValue.split('-');
            //     const day = parseInt(dobParts[0], 10);
            //     const month = parseInt(dobParts[1], 10);
            //     const year = parseInt(dobParts[2], 10);

            //     // Create a Date object from the entered date of birth
            //     const dobDate = new Date(year, month - 1, day); // Months are zero-based in JavaScript

            //     // Today's date
            //     const todayDate = new Date();

            //     // Calculate the difference in milliseconds
            //     const difference = todayDate - dobDate;

            //     // Calculate milliseconds in a day, month, and year
            //     const msPerDay = 1000 * 60 * 60 * 24;
            //     const msPerMonth = msPerDay *
            //         30.436875; // Approximation of the average number of days in a month
            //     const msPerYear = msPerDay * 365.25; // Approximation of the average number of days in a year

            //     // Calculate the difference in years, months, and days
            //     const years = Math.floor(difference / msPerYear);
            //     const remainingDaysAfterYears = difference % msPerYear;
            //     const months = Math.floor(remainingDaysAfterYears / msPerMonth);
            //     const remainingDaysAfterMonths = remainingDaysAfterYears % msPerMonth;
            //     const days = Math.floor(remainingDaysAfterMonths / msPerDay);

            //     // Create a <p> tag to display the calculated age
            //     const resultParagraph = document.createElement('p');
            //     resultParagraph.textContent = `${years} years, ${months} months, and ${days} days old.`;

            //     // Clear previous content in ageResultContainer
            //     ageResultContainer.innerHTML = '';

            //     // Append the <p> tag to the ageResultContainer
            //     ageResultContainer.appendChild(resultParagraph);
            // }



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
            $("#registrationForm").submit(function(e) {
                e.preventDefault();
                SubmitForm("registrationForm", CallBack);

                function CallBack(result) {
                    // console.log(result);
                    var message = result.message;
                    if (result.status) {
                        $("#registrationForm").trigger("reset");
                        $('#imagePreview').attr('src', '{{ url('assets/img/profiles/demo.png') }}');
                        toastr.success(message);
                    } else {
                        toastr.error(message);
                    }
                }
            });


        });
    </script>
@endsection
