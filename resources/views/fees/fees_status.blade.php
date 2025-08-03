@extends('layouts.master')
@section('content')
    @push('title')
        <title>Fees Status</title>
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
                            <li class="breadcrumb-item active">Fees Status</li>
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
                                        <a href="{{ route('fees.list') }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Back"
                                         class="btn btn-primary"><i
                                                class="fas fa-chevron-circle-left"></i></a>
                                    </div>
                                </div>
                            </div>
                            <table class="table border-1 star-student table-hover table-center mb-0 table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>SL</th>
                                        <th>Fees Type</th>
                                        @foreach ($months as $month)
                                            <th>{{ $month }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sl = 0;
                                    @endphp
                                    @foreach ($feesByType as $feeType => $data)
                                        @if ($feeType !== 'Exam Fees')
                                            <tr>
                                                <td>{{ ++$sl }}</td>
                                                <td>{{ $feeType }}</td>
                                                @foreach ($months as $month)
                                                    <td class="text-center">{{ isset($data[$month]) ? $data[$month] : '' }}</td>
                                                @endforeach
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Display Exam Fees at the end of the table -->
                            <table class="table mt-4 border-1 star-student table-hover table-center mb-0 table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th width="5%">SL</th>
                                        <th>Exam Name</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($feesByType['Exam Fees'] as $i => $examFee)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $examFee['examName'] }}</td>
                                            <td>{{ $examFee['amount'] }}</td>
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
@endsection
