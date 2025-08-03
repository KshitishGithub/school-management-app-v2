@extends('layouts.master')
@section('content')
    @push('title')
        <title>Registered</title>
    @endpush
    @php
        define('PAGE', 'registered');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Registered Students</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                                <li class="breadcrumb-item active">Registered Students</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="student-group-form">
                <form action="{{ route('registered') }}" method="get">
                    <div class="row">
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <select class="form-control" name="class" id="selectedClass"
                                    aria-label="Default select example">
                                    <option selected value="">All classes</option>
                                    @if ($classes->isNotEmpty())
                                        @foreach ($classes as $class)
                                            <option {{ Request::get('class') == $class->id ? 'selected' : '' }}
                                                value="{{ $class->id }}">{{ $class->class }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" value="{{ Request::get('name') }}"
                                    placeholder="Search by Name ...">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="father_name"
                                    value="{{ Request::get('father_name') }}" placeholder="Search by father name ...">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="mobile"
                                    value="{{ Request::get('mobile') }}" placeholder="Search by Phone ...">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="search-student-btn">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="search-student-btn">
                                <a href="{{ route('registered') }}" class="btn btn-primary">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table comman-shadow">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">Registered Students</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table
                                    class="table border-1 star-student table-hover table-center mb-0 datatable table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th width='5%'>SL</th>
                                            <th>Registration ID</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>DOB</th>
                                            <th>Parent's Name</th>
                                            <th>Mobile</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($regStudents as $key => $student)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ config('website.registration').$student->id }}
                                                </td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <span class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle"
                                                                src="{{ asset('uploads/images/registration/' . $student->photo) }}"
                                                                alt="User Image">
                                                        </span>
                                                        <a>{{ $student->name }}</a>
                                                    </h2>
                                                </td>
                                                <td>{{ $student->class }}</td>
                                                <td>{{ $student->section ?? 'N/A' }}</td>
                                                <td>{{ $student->dateOfBirth }}</td>
                                                <td>{{ $student->fathersName }}</td>
                                                <td>{{ $student->mobile }}</td>
                                                <td class="text-center">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="true"><i
                                                                class="fas fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-end"
                                                            data-popper-placement="bottom-end"
                                                            style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 22px, 0px);">

                                                            <a class="dropdown-item"
                                                                href="{{ route('registered.edit', ['id' => encrypt($student->id)]) }}"><i
                                                                    class="far fa-edit me-2"></i>Edit</a>

                                                            <a class="dropdown-item" href="javascript:;"
                                                                data-registration_id = "{{ $student->id }}"
                                                                id="registrationPreview"><i
                                                                    class="far fa-eye me-2"></i>View</a>

                                                            <a class="dropdown-item" href="javascript:;"
                                                                data-admit_id = "{{ $student->id }}" id="admitStudents"><i
                                                                    class="far fa-check-circle me-2"></i>Admit</a>

                                                            <a class="dropdown-item"
                                                                href="{{ route('print.registration.form', ['id' => encrypt($student->id)]) }}" target="_blank"><i
                                                                    class="fa fa-print  me-2"
                                                                    aria-hidden="true"></i>Print</a>

                                                            <a class="dropdown-item"
                                                                href="{{ route('download.registration.form', ['id' => encrypt($student->id)]) }}"><i
                                                                    class="fa fa-download  me-2"
                                                                    aria-hidden="true"></i>Download</a>

                                                            <a class="dropdown-item" href="javascript:;" id="deleteStudents" data-delete_id = "{{ $student->id }}"><i
                                                                    class="far fa-trash-alt me-2" ></i>Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Preview Modal --}}
    <div class="modal custom-modal fade invoices-preview" id="PreviewModal" role="dialog" tabindex="-1"
        data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="card invoice-info-card">
                                <div class="card-body pb-0">
                                    <div class="invoice-item invoice-item-one">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="invoice-logo">
                                                    <img src='{{ asset('uploads/images/setting/' . session('admin_settings')[0]['logo']) }} '
                                                        alt="logo">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="invoice-info">
                                                    <div class="invoice-head text-center">
                                                        <h2 class="text-primary">
                                                            {{ session('admin_settings')[0]['school_name'] }}</h2>
                                                        <p>
                                                            {{ session('admin_settings')[0]['village'] }},
                                                            {{ session('admin_settings')[0]['post_office'] }},
                                                            {{ session('admin_settings')[0]['police_station'] }},
                                                            {{ session('admin_settings')[0]['district'] }} ,
                                                            {{ session('admin_settings')[0]['pin_code'] }}
                                                        </p>
                                                        <h4><u>Admission Form</u></h4>
                                                        <p>Session : {{ date('Y') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-3">
                                                <div class="invoice-info">
                                                    <div class="invoice-head">
                                                        <p>Date : 12/01/2023</p>
                                                    </div>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-10">
                                            <span class="fw-bolder fs-5"><u>Personal Details:</u></span>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td scope="row" width="20%">Session : </td>
                                                        <th id="session"></th>
                                                        <td scope="row">Class : </td>
                                                        <th id="class"></th>
                                                        <td scope="row">Section : </td>
                                                        <th id="section"></th>
                                                    </tr>
                                                    <tr>
                                                        <td scope="row">Name : </td>
                                                        <th id="name"></th>
                                                        <td scope="row">Gander : </td>
                                                        <th id="gander"></th>
                                                        <td scope="row">DOB : </td>
                                                        <th id="dob"></th>
                                                    </tr>
                                                    <tr>
                                                        <td scope="row" width="20%">Father's Name :</td>
                                                        <th id="father_name"></th>
                                                        <td scope="row">Father's Qualification : </td>
                                                        <th id="father_qualification"></th>
                                                        <td scope="row">Father's Occupation :</td>
                                                        <th id="father_occupation"></th>
                                                    </tr>
                                                    <tr>
                                                        <td scope="row" width="20%">Mother's Name :</td>
                                                        <th id="mother_name"></th>
                                                        <td scope="row">Mother's Qualification : </td>
                                                        <th id="mother_qualification"></th>
                                                        <td scope="row">Mother's Occupation :</td>
                                                        <th id="mother_occupation"> </th>
                                                    </tr>
                                                    <tr>
                                                        <td scope="row">Religion : </td>
                                                        <th id="religion"></th>
                                                        <td scope="row">Caste : </td>
                                                        <th id="caste"></th>
                                                        <td scope="row">Nationality : </td>
                                                        <th id="nationality"></th>
                                                    </tr>
                                                    <tr>
                                                        <td scope="row" width="20%">Mobile No : </td>
                                                        <th id="mobile"></th>
                                                        <td scope="row" width="20%">WhatsApp No : </td>
                                                        <th id="whatsapp"></th>
                                                        <td scope="row" width="20%">Blood Group : </td>
                                                        <th id="blood_group"></th>
                                                    </tr>
                                                    <tr>
                                                        <td scope="row" width="20%">Transport : </td>
                                                        <th id="transport"></th>
                                                        <td scope="row" width="20%">Hostel : </td>
                                                        <th id="hostel"></th>
                                                        <td scope="row" width="20%">Mess : </td>
                                                        <th id="mess"></th>
                                                    </tr>
                                                    <tr>
                                                        <td scope="row" width="20%">Route : </td>
                                                        <th id="route"></th>
                                                        <td scope="row" width="20%">Bus Stops : </td>
                                                        <th id="stops"></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="col-md-2 mt-4">
                                            <tr class="table-bordered text-center">
                                                <td>
                                                    <img id="photo" class="img-fluid" width="100px" alt="">
                                                </td>
                                            </tr>
                                        </div>
                                        <div class="col-md-12">
                                            <span class="fw-bolder fs-5"><u>Postal Address:</u></span>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td scope="row" width="20%">Village : </td>
                                                        <th id="village"></th>
                                                        <td scope="row" width="20%">Post Office : </td>
                                                        <th id="po"></th>
                                                        <td scope="row">Police Station : </td>
                                                        <th id="ps"></th>
                                                    </tr>
                                                    <tr>
                                                        <td scope="row" width="20%">District : </td>
                                                        <th id="dist"></th>
                                                        <td scope="row" width="20%">PIN : </td>
                                                        <th id="pin"></th>
                                                        <td scope="row">Aadhar : </td>
                                                        <th id="aadhar"></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <hr class="mt-4">
                                        <div class="justify-content-center">
                                            <div class="student-submit">
                                                <button type="button" data-bs-dismiss="modal"
                                                    class="btn btn-danger">Close</button>
                                            </div>
                                        </div>
                                    </div>
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
        $(document).ready(function() {
            // Preview Students
            $(document).on('click', '#registrationPreview', function(e) {
                e.preventDefault();
                var registration_id = $(this).data('registration_id');
                $.ajax({
                    type: "get",
                    url: "{{ route('registered.preview') }}",
                    data: {
                        registration_id: registration_id
                    },
                    beforeSend: function() {
                        $('#overlayer').show();
                    },
                    dataType: "json",
                    success: function(response) {
                        $('#overlayer').hide();
                        var data = response.message
                        console.log(data);
                        if (response.status) {
                            $('#session').text('').text(data.session);
                            $('#class').text('').text(data.class);
                            $('#section').text('').text(data.section);
                            $('#name').text('').text(data.name);
                            $('#gander').text('').text(data.gander);
                            $('#dob').text('').text(data.dateOfBirth);
                            $('#father_name').text('').text(data.fathersName);
                            $('#father_qualification').text('').text(data.fathersQualification);
                            $('#father_occupation').text('').text(data.fathersOccupation);
                            $('#mother_name').text('').text(data.mothersName);
                            $('#mother_qualification').text('').text(data.mothersQualification);
                            $('#mother_occupation').text('').text(data.mothersOccupation);
                            $('#religion').text('').text(data.religion);
                            $('#caste').text(data.caste);
                            $('#nationality').text('').text(data.nationality);
                            $('#mobile').text('').text(data.mobile);
                            $('#whatsapp').text('').text(data.whatsapp);
                            $('#blood_group').text('').text(data.blood_group);
                            $('#transport').text('').text(data.transport);
                            $('#route').text('').text(data.route);
                            $('#stops').text('').text(data.bus_stops);
                            $('#hostel').text('').text(data.hostel);
                            $('#mess').text('').text(data.mess);
                            $('#photo').attr('src', '');
                            $('#photo').attr('src',
                                '{{ url('storage/images/registration/') }}/' + data.photo);
                            $('#village').text('').text(data.village);
                            $('#po').text('').text(data.postOffice);
                            $('#ps').text('').text(data.policeStation);
                            $('#dist').text('').text(data.district);
                            $('#pin').text('').text(data.pin);
                            $('#aadhar').text('').text(data.aadhar);

                            $('#PreviewModal').modal('show');
                        } else {
                            var message = response.message;
                            toastr.error(message);
                        }
                    }
                });
            });



            // Admit Students ............
            $(document).on('click', '#admitStudents', function(e) {
                e.preventDefault();
                var admit_id = $(this).data('admit_id');
                swal({
                        title: "Are you sure?",
                        text: "Want to admit this students!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willAdmit) => {
                        if (willAdmit) {
                            $.ajax({
                                type: "post",
                                url: "{{ route('admit.students') }}",
                                data: {
                                    admit_id: admit_id
                                },
                                beforeSend: function() {
                                    $('#overlayer').show();
                                },
                                dataType: "json",
                                success: function(response) {
                                    console.log(response);
                                    $('#overlayer').hide();
                                    var message = response.message;
                                    if (response.status) {
                                        swal("Good job!", message, "success")
                                            .then((value) => {
                                                window.location.reload();
                                            })
                                    } else {
                                        toastr.error(message);
                                    }
                                }
                            });
                        }
                    });
            });


            // Delete Students ............
            $(document).on('click', '#deleteStudents', function(e) {
                e.preventDefault();
                var delete_id = $(this).data('delete_id');
                swal({
                        title: "Are you sure?",
                        text: "Want to delete this registered students!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willAdmit) => {
                        if (willAdmit) {
                            $.ajax({
                                type: "get",
                                url: "{{ route('delete.students') }}",
                                data: {
                                    delete_id: delete_id
                                },
                                beforeSend: function() {
                                    $('#overlayer').show();
                                },
                                dataType: "json",
                                success: function(response) {
                                    // console.log(response);
                                    $('#overlayer').hide();
                                    var message = response.message;
                                    if (response.status) {
                                        swal("Good job!", message, "success")
                                            .then((value) => {
                                                window.location.reload();
                                            })
                                    } else {
                                        toastr.error(message);
                                    }
                                }
                            });
                        }
                    });
            });

        });
    </script>
@endsection
