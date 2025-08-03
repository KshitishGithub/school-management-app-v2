@extends('layouts.master')
@section('content')
    @push('title')
        <title>Rejected Leave List</title>
    @endpush
    @php
        define('PAGE', 'reject_leave');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">Rejected Leave List</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('leave.pending') }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Pending Leave"
                                         class="btn btn-primary"><i class="bi bi-arrow-left-short"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th width="5">SL</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>Roll</th>
                                            <th>Reason</th>
                                            <th>To Date</th>
                                            <th>From Date</th>
                                            <th>Status</th>
                                            <th>Rejected By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($rejectLeaves->isNotEmpty())
                                            @foreach ($rejectLeaves as $key => $leave)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $leave->name }}</td>
                                                    <td>{{ $leave->class }}</td>
                                                    <td>{{ $leave->section ?? 'N/A' }}</td>
                                                    <td>{{ $leave->roll }}</td>
                                                    <td>{{ $leave->reasons }}</td>
                                                    <td>{{ $leave->to_date }}</td>
                                                    <td>{{ $leave->from_date }}</td>
                                                    <td><span class="badge badge-danger">Rejected</span></td>
                                                    <td>{{ $leave->approvedBy }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
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

