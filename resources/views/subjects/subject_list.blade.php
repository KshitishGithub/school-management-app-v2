@extends('layouts.master')
@section('content')
    @push('title')
        <title>Subjects List</title>
    @endpush
    @php
        define('PAGE', 'subject_list');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Subjects</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Subjects</li>
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
                                        <h3 class="page-title">Subjects List</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('subject.add') }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Add Subject"
                                         class="btn btn-primary"><i
                                                class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if ($subjects->isEmpty())
                                    <p>No subjects found.</p>
                                @else
                                    @foreach ($subjects as $class => $classSubjects)
                                        <div class="col-xl-6 d-flex">
                                            <div class="card flex-fill student-space comman-shadow">
                                                <div class="card-header d-flex align-items-center">
                                                    <h5 class="card-title">Class: {{ $class }}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table
                                                            class="table star-student table-hover table-center table-borderless table-striped">
                                                            <thead class="thead-light">
                                                                <tr>
                                                                    <th>Sl</th>
                                                                    <th class="text-center">Subject</th>
                                                                    <th class="text-end">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php $i = 1; @endphp
                                                                @foreach ($classSubjects as $subject)
                                                                    <tr>
                                                                        <td class="text-nowrap">{{ $i++ }}</td>
                                                                        <td class="text-center">{{ $subject->subject }}</td>
                                                                        <td class="text-end">
                                                                            <div class="edit-delete-btn">
                                                                                <a href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Delete Subject"
                                                                                 data-subject_id="{{ $subject->id }}" class="text-danger" id="subjectDltBtn"><i class="feather-trash-2 me-1"></i>Delete</a>
                                                                            </div>
                                                                        </td>
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
@section('customJS')
    <script>
        $(document).ready(function() {

            // Delete .........

            $(document).on("click", "#subjectDltBtn", function(e) {
                e.preventDefault();
                var subject_id = $(this).data("subject_id");
                DeleteRecord(subject_id, "{{ route('subject.delete') }}", CallBack);

                function CallBack(result) {
                    $('#overlayer').hide();
                    console.log(result);
                    if (result.status == true) {
                        swal("Good job!", result.message, "success")
                            .then((value) => {
                                window.location.reload();
                            })
                    } else {
                        var message = result.message;
                        toastr.error(message);
                    }
                }
            });
        });
    </script>
@endsection
