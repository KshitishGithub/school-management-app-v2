@extends('layouts.master')
@section('content')
    @push('title')
        <title>Fees Details</title>
    @endpush
    @php
        define('PAGE', 'fees');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        {{-- <h3 class="page-title">Fees Details</h3> --}}
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('fees.list') }}">Fees Collect</a></li>
                            <li class="breadcrumb-item active">Fees Details</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">Fees Details</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('fees.list') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title data-bs-original-title="Back" class="btn btn-primary"><i
                                                class="fas fa-chevron-circle-left"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table
                                    class="table border-1 star-student table-bordered table-hover table-center mb-0 datatable table-striped">
                                    <thead class="student-thread">
                                        <tr class="text-center">
                                            <th>SL</th>
                                            <th>Date</th>
                                            <th>Fees Type</th>
                                            {{-- <th>Exam Name</th>
                                            <th>Month</th> --}}
                                            <th>Amount</th>
                                            {{-- <th>Status</th>
                                            <th>Remarks</th> --}}
                                            <th>PDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        {{-- @foreach ($Fees as $key => $feesDetail)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ \Carbon\Carbon::parse($feesDetail->created_at)->format('d-M-Y - h:i:s') }}
                                                </td>
                                                <td>{{ $feesDetail->fees_type }}</td>
                                                <td>{{ $feesDetail->exam_name ?? 'N/A' }}</td>
                                                <td>{{ $feesDetail->month ?? 'N/A' }}</td>
                                                <td>{{ $feesDetail->amount }}</td>
                                                <td>
                                                    @if ($feesDetail->status == 'Paid')
                                                        <span class="badge bg-success">Paid</span>
                                                    @else
                                                        <span class="badge bg-danger">Due</span>
                                                    @endif
                                                </td>
                                                <td>{{ Str::limit($feesDetail->remarks, 50) ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Print Fees"
                                                        href="{{ route('print.fees', encrypt($feesDetail->id)) }}" target="_blank"><i
                                                            class="fa fa-print text-primary me-2"></i></a>
                                                    {{-- <a data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Download Fees"
                                                        href="{{ route('fees.download', encrypt($feesDetail->id)) }}"><i
                                                            class="fa fa-download text-danger me-2"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach --}}
                                        @php $counter = 1; @endphp
                                        @foreach ($Fees as $date => $data)
                                            <tr>
                                                <td>{{ $counter++ }}</td>
                                                <td>{{ $date }}</td>
                                                <td>{{ implode(', ', $data['combined_fees_types']) }}</td>
                                                <td>{{ $data['total_amount'] }}</td>
                                                <td class="text-center">
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Print Fees"
                                                        href="{{ route('print.fees',[ encrypt($date),encrypt($id)]) }}" target="_blank"><i
                                                            class="fa fa-print text-primary me-2"></i></a>
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
@endsection
