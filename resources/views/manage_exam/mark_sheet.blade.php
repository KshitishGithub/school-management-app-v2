@extends('layouts.master')
@section('content')
    @push('title')
        <title>Mark Sheet</title>
    @endpush
    @php
        define('PAGE', 'mark_sheet');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Mark Sheet</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Mark Sheet</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="student-group-form">
                <form action="{{ route('exam.mark_sheet') }}" method="get">
                    <div class="row">
                        <div class="col-lg-3 col-md-5">
                            <div class="form-group">
                                <select class="form-control" name="exams" id="selectedExam"
                                    aria-label="Default select example">
                                    <option selected value="">Choose exam</option>
                                    @if ($exams->isNotEmpty())
                                        @foreach ($exams as $exam)
                                            <option {{ Request::get('exams') == $exam->id ? 'selected' : '' }}
                                                value="{{ $exam->id }}">{{ $exam->exam_name }} - {{ $exam->class }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <div class="search-student-btn">
                                    <button type="btn" type="submit" class="btn btn-primary">Search</button>
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
                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th width="5%">Sl</th>
                                            <th>Registration No</th>
                                            <th>Name</th>
                                            <th>Fathers Name</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th width="10%">Mobile</th>
                                            <th width="10%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $i => $student)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ config('website.registration') . $student->registration_id }}</td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->fathersName }}</td>
                                                <td>{{ $student->class }}</td>
                                                <td>{{ $student->section ?? 'N/A' }}</td>
                                                <td>{{ $student->mobile }}</td>
                                                <td class="text-center">
                                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Print Mark Sheet"
                                                        href="{{ route('exam.marksheet.print', ['exam_id'=>request()->input('exams'),'registration_id' => $student->registration_id,]) }}"
                                                        target="_blank"><i class="fa fa-print text-primary me-2"></i></a>
                                                    {{--  <a data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Download Mark Sheet"
                                                        href="{{ route('exam.marksheet.download', ['exam_id'=>request()->input('exams'),'registration_id' => $student->registration_id,]) }}"><i
                                                            class="fa fa-download text-danger me-2"></i></a>  --}}
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
