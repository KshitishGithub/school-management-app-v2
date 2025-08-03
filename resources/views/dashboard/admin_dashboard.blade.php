@extends('layouts.master')
@section('content')
    @push('title')
        <title>Admin Dashboard</title>
    @endpush
    @php
        define('PAGE', 'admin_dashboard');
    @endphp
    {{-- message --}}
    {{-- {!! Toastr::message() !!} --}}
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">WWelcome, {{ auth()->user()->name }} !</h3>
                            {{-- <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active"></li>
                            </ul> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Students</h6>
                                    <h3>{{ $data['total_students'] }}+</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="assets/img/icons/dash-icon-01.svg" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Teachers</h6>
                                    <h3>{{ $data['total_teachers'] }}+</h3>
                                </div>
                                <div class="db-icon">
                                    <img height="60" src="assets/img/dashboard/teachers.png" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Total Class</h6>
                                    <h3>{{ $data['total_class'] }}+</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="assets/img/icons/student-icon-01.svg" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="card bg-comman w-100">
                        <div class="card-body">
                            <div class="db-widgets d-flex justify-content-between align-items-center">
                                <div class="db-info">
                                    <h6>Today revenue</h6>
                                    <h3>&#8377;{{ $data['total_revenue'] }}.00/-</h3>
                                </div>
                                <div class="db-icon">
                                    <img src="assets/img/icons/dash-icon-04.svg" alt="Dashboard Icon">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card flex-fill bg-warning sm-box">
                        <div class="social-likes">
                            <p>Registered Students</p>
                            <h6>{{ $data['registered_student'] }}</h6>
                        </div>
                        <div class="social-boxs">
                            <img class="img-fluid rounded" src="assets/img/dashboard/registered.png" alt="Social Icon">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card flex-fill bg-success sm-box">
                        <div class="social-likes">
                            <p>In students (school)</p>
                            <h6>{{ $data['present_student'] }}</h6>
                        </div>
                        <div class="social-boxs">
                            <img class="img-fluid rounded" src="assets/img/dashboard/student.png" alt="Social Icon">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card flex-fill bg-danger sm-box">
                        <div class="social-likes">
                            <p>Out Students (school)</p>
                            <h6>{{ $data['out_student'] }}</h6>
                        </div>
                        <div class="social-boxs">
                            <img class="img-fluid rounded" src="assets/img/dashboard/registered.png" alt="Social Icon">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card flex-fill insta sm-box">
                        <div class="social-likes">
                            <p>Last month revenue</p>
                            <h6>&#8377;{{ $data['last_month'] }}.00/-</h6>
                        </div>
                        <div class="social-boxs">
                            <img class="img-fluid rounded" src="assets/img/dashboard/rupees.jpg" alt="Social Icon">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card flex-fill bg-info sm-box">
                        <div class="social-likes">
                            <p>Last 7 days revenue</p>
                            <h6>&#8377;{{ $data['last_seven_days'] }}.00/-</h6>
                        </div>
                        <div class="social-boxs">
                            <img class="img-fluid rounded" src="assets/img/dashboard/payment.jpg" alt="Social Icon">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card flex-fill bg-secondary sm-box">
                        <div class="social-likes">
                            <p>Total Hosteller</p>
                            <h6>{{ $data['total_hosteller'] }}</h6>
                        </div>
                        <div class="social-boxs">
                            <img class="img-fluid rounded" src="assets/img/dashboard/registered.png" alt="Social Icon">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card flex-fill sm-box" style="background-color: rgb(71, 242, 91)">
                        <div class="social-likes">
                            <p>Present in hostel</p>
                            <h6>{{ $data['present_hosteller'] }}</h6>
                        </div>
                        <div class="social-boxs">
                            <img class="img-fluid rounded" src="assets/img/dashboard/student.png" alt="Social Icon">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card flex-fill sm-box" style="background-color: rgb(239, 25, 236)">
                        <div class="social-likes">
                            <p>Evening Hostel Attendance</p>
                            <h6>{{ $data['evening_hostel_student'] }}</h6>
                        </div>
                        <div class="social-boxs">
                            <img class="img-fluid rounded" src="assets/img/dashboard/registered.png" alt="Social Icon">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h5 class="card-title">Girls and boys overview</h5>
                                </div>
                                <div class="col-6">
                                    <ul class="chart-list-out">
                                        <li><span class="circle-blue"></span>Girls</li>
                                        <li><span class="circle-green"></span>Boys</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="apexcharts-area"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6">
                    <div class="card card-chart">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <h5 class="card-title">Number of Students</h5>
                                </div>
                                {{-- <div class="col-6">
                                    <ul class="chart-list-out">
                                        <li><span class="circle-blue"></span>Girls</li>
                                        <li><span class="circle-green"></span>Boys</li>
                                    </ul>
                                </div> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="radial-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill student-space comman-shadow">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title">Recently Registered</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table star-student table-hover table-center table-borderless table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width='5%'>SL</th>
                                            <th>Registration ID</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>DOB</th>
                                            <th>Mobile</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($regStudents as $key => $student)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ config('website.registration') . $student->id }}
                                                </td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <span class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle"
                                                                src="{{ Storage::url('images/registration/' . $student->photo) }}"
                                                                alt="User Image">
                                                        </span>
                                                        <a>{{ $student->name }}</a>
                                                    </h2>
                                                </td>
                                                <td>{{ $student->class }}</td>
                                                <td>{{ $student->section ?? 'N/A' }}</td>
                                                <td>{{ $student->dateOfBirth }}</td>
                                                <td>{{ $student->mobile }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-xl-6 d-flex">
                    <div class="card flex-fill comman-shadow">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title ">Recently Fees Collection</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table border-1 star-student table-hover table-center mb-0 table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th>Date</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            <th>Fees Type</th>
                                            <th>Exam Name</th>
                                            <th>Month</th>
                                            <th>Amount</th>
                                            <th>Receiver</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentFees as $key => $feesDetail)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($feesDetail->created_at)->format('d-m-Y') }}</td>
                                                <td>{{ $feesDetail->name }}</td>
                                                <td>{{ $feesDetail->class }}</td>
                                                <td>{{ $feesDetail->fees_type }}</td>
                                                <td>{{ $feesDetail->exam_name ?? 'N/A' }}</td>
                                                <td>{{ $feesDetail->month ?? 'N/A' }}</td>
                                                <td>{{ $feesDetail->amount }}</td>
                                                <td>{{ $feesDetail->receiver }}</td>
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
@endsection
{{-- Charts --}}
@section('customJS')
    <script>
        'use strict';
        $(document).ready(function() {
            // Overview
            if ($('#apexcharts-area').length > 0) {
                var options = {
                    chart: {
                        height: 350,
                        type: "area",
                        toolbar: {
                            show: true
                        },
                    },
                    dataLabels: {
                        enabled: true,
                    },
                    stroke: {
                        curve: "smooth"
                    },
                    series: [{
                        name: "Girls",
                        color: '#3D5EE1',
                        data: {!! json_encode($data['charts']['girls']) !!}
                    }, {
                        name: "Boys",
                        color: '#70C4CF',
                        data: {!! json_encode($data['charts']['boys']) !!}
                    }],
                    xaxis: {
                        categories: {!! json_encode($data['charts']['class']) !!}
                    }
                }
                var chart = new ApexCharts(document.querySelector("#apexcharts-area"), options);
                chart.render();
            }

            // Radial chart
            if ($('#radial-chart').length > 0) {
                var radialChart = {
                    chart: {
                        height: 365,
                        type: 'radialBar',
                        toolbar: {
                            show: true,
                        }
                    },
                    plotOptions: {
                        radialBar: {
                            dataLabels: {
                                name: {
                                    fontSize: '22px',
                                },
                                value: {
                                    fontSize: '16px',
                                },
                                total: {
                                    show: false,
                                    label: 'Total Students',
                                    formatter: function(w) {
                                        return 249
                                    }
                                }
                            }
                        }
                    },
                    series: {!! json_encode($data['charts']['students']) !!},
                    labels: {!! json_encode($data['charts']['class']) !!},
                }
                var chart = new ApexCharts(document.querySelector("#radial-chart"), radialChart);
                chart.render();
            }


        });
    </script>
@endsection
