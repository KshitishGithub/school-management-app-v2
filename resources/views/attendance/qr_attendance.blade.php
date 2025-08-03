@extends('layouts.master')
@section('content')
    @push('title')
        <title>QR Attendance</title>
    @endpush
    @php
        define('PAGE', 'qr_attendance');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">In QR Attendance System</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">QR Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="row">
                                <div class="card card-primary p-0">
                                    <div class="page-header">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <div class="card-header">
                                                    <h5 class="card-title">Attendance Panels</h5>
                                                </div>
                                            </div>
                                            <div class="col-auto text-end float-end ms-auto download-grp">
                                                <a href="{{ route('attendance.view') }}" class="btn btn-primary">View
                                                    Attendance</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mx-auto">
                                        <div class="card border-0 m-0 shadow-lg">
                                            <div class="card-body">
                                                <div class="form-group p-0">
                                                    <select name="options" class="form-control mb-3">
                                                        <option value="1">Front Camera</option>
                                                        <option selected value="2">Back Camera</option>
                                                    </select>
                                                    <video id="preview" style="height:auto";
                                                        class="form-control p-0"></video>
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
    </div>
    {{-- Modal --}}
    <div id="ModalData">

    </div>
@endsection
@section('customJS')
    <script type="text/javascript">
        // Fill attendance data
        function qr_attendance(registration_id, session, className, section, roll, value, type) {
            if (value !== '') {
                var data = {
                    registration_id: registration_id,
                    session: session,
                    class: className,
                    section: section ?? '',
                    roll: roll,
                    attendance: value,
                    attendance_type: type,
                }
                // console.log(data);
                $.ajax({
                    url: "{{ route('attendance.fill_attendnce') }}",
                    type: "post",
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $("#overlayer").show();
                    },
                    success: function(response) {
                        $("#overlayer").hide();
                        // console.log(response);
                        if (response.status) {
                            var attendanceValue = response.attendance;
                            var currentDate = response.time;
                            Swal.fire({
                                title: "Good job!",
                                text: response.message,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1000
                            });
                            var sound = document.getElementById('welcome');
                            sound.play();
                        }else{
                            Swal.fire({
                                title: "Opps !",
                                text: response.message,
                                icon: "error",
                                showConfirmButton: false,
                                timer: 1000
                            });
                            var sound = document.getElementById('error-sound');
                            sound.play();
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#overlayer").hide();
                        console.error('Error occurred while fetching attendance:', xhr, status, error);
                    }
                })
            }
        }

        // Open camera and run the attendance fill function
        var scanner = new Instascan.Scanner({
            video: document.getElementById('preview'),
            scanPeriod: 5,
            mirror: false
        });

        scanner.addListener('scan', function(content) {
            $(document).ready(function() {
                var decodedContent = decodeURIComponent(content);
                var values = decodedContent.split(',');
                // console.log(values);
                if (values.length > 5) {
                    alert("Invalid QR code!");
                } else {
                    var registration_id = values[0];
                    var session = values[1];
                    var className = values[2];
                    var section = values[3];
                    var roll = values[4];

                    if (
                        (
                            registration_id !== null && typeof registration_id === 'string' &&
                            registration_id !== '' &&
                            session !== null && typeof session === 'string' && session !== '' &&
                            className !== null && typeof className === 'string' && className !== '' &&
                            roll !== null && typeof roll === 'string' && roll !== ''
                        )
                    ) {
                        // alert('OK');
                        qr_attendance(registration_id, session, className, section, roll, "P", "QR");
                    } else {
                        alert('Invalid QR Code');
                    }

                }
            });
        });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                var backCameraIndex = -1;
                var frontCameraIndex = -1;

                // Check which cameras are available
                cameras.forEach((camera, index) => {
                    if (camera.name.toLowerCase().includes('back')) {
                        backCameraIndex = index;
                    } else if (camera.name.toLowerCase().includes('front')) {
                        frontCameraIndex = index;
                    }
                });

                if (backCameraIndex !== -1) {
                    // Start with the back camera
                    scanner.start(cameras[backCameraIndex]);
                } else if (frontCameraIndex !== -1) {
                    // Fall back to the front camera
                    scanner.start(cameras[frontCameraIndex]);
                } else {
                    // If no specific back or front camera is found, start the first available camera
                    scanner.start(cameras[0]);
                }

                $('[name="options"]').on('change', function() {
                    if ($(this).val() == 1 && frontCameraIndex !== -1) {
                        scanner.start(cameras[frontCameraIndex]);
                    } else if ($(this).val() == 2 && backCameraIndex !== -1) {
                        scanner.start(cameras[backCameraIndex]);
                    } else {
                        alert('Selected camera not found!');
                    }
                });
            } else {
                console.error('No cameras found.');
                alert('No cameras found.');
            }
        }).catch(function(e) {
            console.error(e);
            alert(e);
        });
    </script>
@endsection
