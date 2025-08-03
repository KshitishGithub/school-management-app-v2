@extends('layouts.master')
@section('content')
@push('title')
        <title>Students Profile</title>
    @endpush
    @php
        define('PAGE', 'students_profile');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <h3 class="page-title">Student Details</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                <li class="breadcrumb-item active">Student Profile</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="about-info">
                                <h4>Profile <span><a href="javascript:;"><i class="feather-more-vertical"></i></a></span></h4>
                            </div>
                            <div class="student-profile-head">
                                <div class="profile-bg-img">
                                    <img src="{{ URL::to('assets/img/profile-bg.jpg') }}" alt="Profile">
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4">
                                        <div class="profile-user-box">
                                            <div class="profile-user-img">
                                                <img src="" alt="Profile">
                                                <div class="form-group students-up-files profile-edit-icon mb-0">
                                                    <div class="uplod d-flex">
                                                        <label class="file-upload profile-upbtn mb-0">
                                                            <img src="{{ asset('uploads/images/registration/' . $students->photo) }}" alt="Profile">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="student-personals-grp">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="heading-detail">
                                            <h4><u>Personal Details :</u></h4>
                                        </div>
                                        <div class="personal-activity">
                                            <div class="personal-icons">
                                                <i class="feather-user"></i>
                                            </div>
                                            <div class="views-personal">
                                                <h4>Name</h4>
                                                <h5>{{$students->name}}</h5>
                                            </div>
                                        </div>
                                        <div class="personal-activity">
                                            <div class="personal-icons">
                                                <i class="feather-award"></i>
                                            </div>
                                            <div class="views-personal">
                                                <h4>Section </h4>
                                                <h5>{{$students->section}}</h5>
                                            </div>
                                        </div>
                                        <div class="personal-activity">
                                            <div class="personal-icons">
                                                <i class="feather-edit-2"></i>
                                            </div>
                                            <div class="views-personal">
                                                <h4>Roll No</h4>
                                                <h5>#{{$students->roll_no}}</h5>
                                            </div>
                                        </div>
                                        <div class="personal-activity">
                                            <div class="personal-icons">
                                                <i class="feather-phone-call"></i>
                                            </div>
                                            <div class="views-personal">
                                                <h4>Mobile</h4>
                                                <h5>{{$students->mobile}}</h5>
                                            </div>
                                        </div>
                                        <div class="personal-activity">
                                            <div class="personal-icons">
                                                <i class="feather-user"></i>
                                            </div>
                                            <div class="views-personal">
                                                <h4>Gender</h4>
                                                <h5>{{$students->gander}}</h5>
                                            </div>
                                        </div>
                                        <div class="personal-activity">
                                            <div class="personal-icons">
                                                <i class="feather-calendar"></i>
                                            </div>
                                            <div class="views-personal">
                                                <h4>Date of Birth</h4>
                                                <h5>{{$students->dateOfBirth}}</h5>
                                            </div>
                                        </div>
                                        <div class="personal-activity">
                                            <div class="personal-icons">
                                                <i class="feather-info"></i>
                                            </div>
                                            <div class="views-personal">
                                                <h4>Religion</h4>
                                                <h5>{{$students->religion}}</h5>
                                            </div>
                                        </div>
                                        <div class="personal-activity">
                                            <div class="personal-icons">
                                                <i class="feather-italic"></i>
                                            </div>
                                            <div class="views-personal">
                                                <h4>Caste</h4>
                                                <h5>{{$students->caste}}</h5>
                                            </div>
                                        </div>
                                        <div class="personal-activity mb-0">
                                            <div class="personal-icons">
                                                <i class="feather-map-pin"></i>
                                            </div>
                                            <div class="views-personal">
                                                <h4>Address</h4>
                                                <h5>{{$students->village}},{{$students->postOffice}},{{$students->policeStation}},{{$students->district}},{{$students->pin}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="student-personals-grp">
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="heading-detail">
                                            <h4><u>Parents Detalis:</u></h4>
                                        </div>
                                        <div class="hello-park">
                                            <p>Father's Name:</p>
                                            <p><b>{{ $students->fathersName }}</b></p>
                                        </div>
                                        <div class="hello-park">
                                            <p>Father's Qualification:</p>
                                            <p><b>{{ $students->fathersQualification }}</b></p>
                                        </div>
                                        <div class="hello-park">
                                            <p>Father's Occupation:</p>
                                            <p><b>{{ $students->fathersOccupation }}</b></p>
                                        </div>
                                        <div class="hello-park">
                                            <p>Mother's Name:</p>
                                            <p><b>{{ $students->mothersName }}</b></p>
                                        </div><div class="hello-park">
                                            <p>Mother's Qualification:</p>
                                            <p><b>{{ $students->mothersQualification }}</b></p>
                                        </div><div class="hello-park">
                                            <p>Mother's Occupation:</p>
                                            <p><b>{{ $students->mothersOccupation }}</b></p>
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
