@extends('layouts.master')
@section('content')
    @push('title')
        <title>Fees Collection</title>
    @endpush
    @php
        define('PAGE', 'fees');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Collect Fees</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Collect Fees</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="student-group-form">
                <form action="{{ route('fees.list') }}" method="get">
                    <div class="row">
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <select class="form-control" name="class" id="selectedClass" aria-label="Default select example">
                                    <option value="">Select class</option>
                                    @if ($classes->isNotEmpty())
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" @if (Request::has('class') && Request::get('class') == $class->id) selected @elseif ($loop->first) selected @endif>
                                                {{ $class->class }}
                                            </option>
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
                                <a href="{{ route('fees.list') }}" class="btn btn-primary">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                @foreach ($students as $key => $student)
                    <div class="col-sm-6 col-lg-4 col-xl-3 d-flex">
                        <div class="card invoices-grid-card w-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <a href="javascript:void(0)"
                                    class="invoice-grid-link">{{ config('website.registration') . $student->id }} -
                                    {{ $student->class }} - {{ $student->section ?? 'N/A' }} - {{ $student->roll_no }}</a>
                                {{-- <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="true"><i class="fas fa-ellipsis-v"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end" data-popper-placement="bottom-end"
                                        style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 22px, 0px);">
                                        <a class="dropdown-item" href="{{ route('fees.add', ['id' => $student->id]) }}"><i
                                                class="fa fa-plus-circle me-2" aria-hidden="true"></i>Add Fees</a>
                                        <a class="dropdown-item"
                                            href="{{ route('fees.details', ['id' => $student->id]) }}"><i
                                                class="fa fa-eye me-2" aria-hidden="true"></i>Details</a>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="card-middle">
                                <h2 class="card-middle-avatar">
                                    <a href="javascript:void(0)">
                                        <img class="avatar avatar-sm me-2 avatar-img rounded-circle"
                                            src="{{ Storage::url('images/registration/' . $student->photo) }}"
                                            alt="User Image">
                                            {{ $student->name }}</a>
                                </h2>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <span><i class="fa-solid fa-mobile-screen"></i> Mobile</span>
                                        <h6 class="mb-0">+91-{{ $student->mobile }}</h6>
                                    </div>
                                    <div class="col-auto">
                                        <span><i class="far fa-calendar-alt"></i>  Date of Birth</span>
                                        <h6 class="mb-0">{{ $student->dateOfBirth }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-4">
                                        <a href="{{ route('fees.add', ['id' => encrypt($student->id)]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Collect Fees"
                                             class="badge bg-success text-light"><i class="fa-solid fa-indian-rupee-sign"></i> </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('fees.status', ['id' => encrypt($student->id)]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Fees Status"
                                             class="badge bg-secondary text-light"><i class="fa-regular fa-eye"></i></a>
                                    </div>
                                    <div class="col-4">
                                        <a href="{{ route('fees.details', ['id' => encrypt($student->id)]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Fees Details"
                                             class="badge bg-info text-light"><i class="fa-solid fa-share"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
