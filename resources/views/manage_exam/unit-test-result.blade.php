@extends('layouts.master')
@section('content')
@push('title')
<title>Unit Test Result</title>
@endpush
@php
define('PAGE', 'unitTestResult');
@endphp
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Unit Test Result</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                        <li class="breadcrumb-item active">Unit Test Result</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="student-group-form">
            <form action="{{ route('exam.unitTest.result') }}" method="get">
                <div class="row">
                    <div class="col-lg-3 col-md-5">
                        <div class="form-group">
                            <select class="form-control" name="exams" id="selectedExam"
                                aria-label="Default select example">
                                <option selected value="">Choose exam</option>
                                @if ($exams->isNotEmpty())
                                @foreach ($exams as $exam)
                                <option {{ Request::get('exams')==$exam->id ? 'selected' : '' }}
                                    value="{{ $exam->id }}">{{ $exam->exam_name }} - {{ $exam->class }}
                                    {{ $exam->section !== null ? - $exam->section : ''}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <div class="search-student-btn">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @include('layouts.message')
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="page-header">
                            <div class="row align-items-center">
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    <a href="#" id="downloadPdfBtn" class="btn btn-outline-primary me-2">
                                        <i class="fas fa-download"></i> Download PDF
                                    </a>
                                    <a href="#" class="btn btn-outline-primary me-2" id="downloadTable">
                                        <i class="fas fa-download"></i> Download CSV
                                    </a>
                                </div>
                            </div>
                        </div>
                        @if ($studentResults)
                        <div class="table-responsive">
                            <table id="DataList"
                                class="table border-1 table-bordered star-student table-hover table-center mb-0 table-striped">
                                <thead class="student-thread">
                                    <tr class="text-center">
                                        <th width="5%">SL</th>
                                        <th>Name</th>
                                        <th width="7%">Class</th>
                                        {{ $exam->section !== null ? '<th>'.$exam->section.'</th>' : '' }}
                                        <th width="5%">Roll No</th>
                                        <th width="7%">Full Marks</th>
                                        <th width="7%">Pass Marks</th>
                                        <th width="7%">Marks obtained</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($studentResults as $i => $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student['name'] }}</td>
                                        <td>{{ $student['class'] }}</td>
                                        {{ $student['section'] !== null ? '<td>'.$student['section'].'</td>' : '' }}
                                        <td>{{ $student['roll_no'] }}</td>
                                        <td>{{ $student['total_marks'] }}</td>
                                        <td>{{ $student['total_pass_marks'] }}</td>
                                        <td>{{ $student['total_marks_obtained'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script for table download --}}
<script>
    document.getElementById('downloadTable').addEventListener('click', function () {
            var table = document.getElementById('DataList');
            var rows = [...table.rows].map(row => [...row.cells].map(cell => cell.innerText).join(',')).join('\n');

            var csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Unit Test Result\n\n"; // Add your custom header here
            csvContent += rows;

            var link = document.createElement('a');
            link.setAttribute('href', encodeURI(csvContent));
            link.setAttribute('download', 'Unit_Test_Result.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
</script>
@endsection
@section('customJS')
<script>
    document.getElementById('downloadPdfBtn').addEventListener('click', function (e) {
        // Get the selected exam value
        var selectedExam = document.getElementById('selectedExam').value;

        // Check if an exam is selected
        if (selectedExam === "") {
            alert("Please select an exam before downloading the PDF.");
            e.preventDefault(); // Prevent redirection if no exam is selected
        } else {
            // Redirect to download PDF route with the selected exam
            window.location.href = "{{ route('exam.unitTestResult.downloadPDF') }}?exams=" + selectedExam;
        }
    });
</script>
@endsection
