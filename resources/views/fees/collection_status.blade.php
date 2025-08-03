@extends('layouts.master')
@section('content')
    @push('title')
        <title>Fees Collection Status</title>
    @endpush
    @php
        define('PAGE', 'collection_status');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Fees Collection Status</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Fees Collection Status</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="student-group-form">
                <form action="{{ route('fees.collection.status') }}" method="get">
                    <div class="row">
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control datetimepicker" name="start_date" placeholder="Select Start Date" required
                                    value="{{ Request::get('start_date') }}">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control datetimepicker" name="end_date" placeholder="Select End Date" required
                                    value="{{ Request::get('end_date') }}">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="search-student-btn">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="search-student-btn">
                                <a href="{{ route('fees.collection.status') }}" class="btn btn-primary">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="page-title">Total Amount: {{ $totalAmount }}.00/-</h4>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('fees.list') }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Back to list"
                                         class="btn btn-primary"><i
                                                class="fas fa-chevron-circle-left"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table
                                    class="table border-1 star-student table-hover table-center mb-0 table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th>Date/Time</th>
                                            <th>Fees Type</th>
                                            <th>Exam Name</th>
                                            <th>Month</th>
                                            <th>Amount</th>
                                            <th>Receiver</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($feesCollectionStatus as $key => $feesDetail)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($feesDetail->created_at)->format('d-M-Y') }}
                                                </td>
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
