@extends('layouts.master')
@section('content')
@push('title')
        <title>Section List</title>
    @endpush
    @php
        define('PAGE', 'section_list');
    @endphp
    {{-- message --}}
    {{-- {!! Toastr::message() !!} --}}
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Section</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Section</li>
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
                                        <h3 class="page-title">Sections list</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{route('section.add')}}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Add new section"
                                         class="btn btn-primary"><i
                                                class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                @if ($sections->isEmpty())
                                    <p>No sections found.</p>
                                @else
                                    @foreach ($sections as $section => $classsections)
                                        <div class="col-xl-6 d-flex">
                                            <div class="card flex-fill student-space comman-shadow">
                                                <div class="card-header d-flex align-items-center">
                                                    <h5 class="card-title">Class: {{ $section }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table
                                                            class="table star-student table-hover table-center table-borderless table-striped">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Sl</th>
                                                                    <th class="text-center">Section</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php $i = 1; @endphp
                                                                @foreach ($classsections as $section)
                                                                    <tr>
                                                                        <td class="text-nowrap">{{ $i++ }}</td>
                                                                        <td class="text-center">{{ $section->section }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


