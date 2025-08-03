@extends('layouts.master')
@section('content')
    @push('title')
        <title>Published Exam List</title>
    @endpush
    @php
        define('PAGE', 'published');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Published Exam</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                            <li class="breadcrumb-item active">Published Exam list</li>
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
                                        <h3 class="page-title">Published Exam list</h3>
                                    </div>
                                    {{--  <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('exam.add') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title data-bs-original-title="Add new exam" class="btn btn-primary"><i
                                                class="fas fa-plus"></i></a>
                                    </div>  --}}
                                </div>
                            </div>


                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th width="5%">Sl</th>
                                            <th>Exam Name</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th width="10%">Fees</th>
                                            <th width="10%">Publish</th>
                                            <th width="10%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($examsData as $i => $exams)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $exams->exam_name }}</td>
                                                <td>{{ $exams->class }}</td>
                                                <td>{{ $exams->section ?? 'N/A' }}</td>
                                                <td>{{ $exams->fees }}</td>
                                                <td>
                                                    @if ($exams->is_published)
                                                        <span class="badge badge-success">Published</span>
                                                    @else
                                                        <span class="badge badge-info">Running</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="javascript:;" class="btn btn-sm bg-success-light me-2"
                                                            data-bs-toggle="modal" data-bs-target="#modalTop"
                                                            id="show_exam_details" data-exam_id="{{ $exams->id }}">
                                                            <i class="feather-eye text-success" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title
                                                                data-bs-original-title="Exam Details"></i>
                                                        </a>
                                                        <a href="javascript:;" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title
                                                            data-bs-original-title="Unpublish Result"
                                                            class="btn btn-sm bg-danger-light"
                                                            data-exam_id="{{ $exams->id }}" id="UnpublishBtn">
                                                            <i class="feather-x-square text-warning"></i>
                                                        </a>

                                                        <a href="javascript:;" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title
                                                            data-bs-original-title="Delete Exam"
                                                            class="btn btn-sm bg-danger-light ml-2"
                                                            data-exam_id="{{ $exams->id }}" id="deleteExam">
                                                            <i class="feather-trash-2 text-danger"></i>
                                                        </a>
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
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTop" tabindex="-1" role="dialog" aria-labelledby="modalTopLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTopLabel"><span><b>Exams details:</b></span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered table-info table-hovered">
                        <thead class="text-center">
                            <th width="2%">Sl</th>
                            <th>Subject</th>
                            <th>Exam date</th>
                            <th>Exam day</th>
                            <th>Start time</th>
                            <th>End time</th>
                            <th>Full marks</th>
                            <th>Pass marks</th>
                            <th>Subject Type</th>
                        </thead>
                        <tbody class="text-center" id="table_data">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('customJS')
    <script>
        $(document).ready(function() {
            // Change exam status

            $(document).on("click", '#show_exam_details', function(e) {
                e.preventDefault();
                var id = $(this).data("exam_id");
                $.ajax({
                    type: "get",
                    url: "{{ route('exam.details') }}",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#overlayer').show();
                    },
                    success: function(response) {
                        // console.log(response);
                        $('#overlayer').hide();
                        if (response.status) {
                            $('#table_data').html('');
                            $('#table_data').append(response.data);
                            $('#modalTop').click();
                        } else {
                            alert("No data available");
                        }

                    }
                });
            });

            // Delete exam ........
            $(document).on("click", '#deleteExam', function(e) {
                e.preventDefault();
                var id = $(this).data("exam_id");
                swal({
                        title: "Are you sure want to Delete this exam with associated subjects and numbers ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "get",
                                url: "{{ route('exam.delete') }}",
                                data: {
                                    id: id
                                },
                                dataType: "json",
                                beforeSend: function() {
                                    $('#overlayer').show();
                                },
                                success: function(response) {
                                    $('#overlayer').hide();
                                    var message = response.message;
                                    if (response.status) {
                                        $("#ExamAddForm").trigger("reset");
                                        toastr.success(message);
                                    } else {
                                        toastr.error(message);
                                    }

                                }
                            });
                        }
                    });
            });


            // Publish button ........
            $(document).on("click", '#UnpublishBtn', function(e) {
                e.preventDefault();
                var id = $(this).data("exam_id");
                swal({
                        title: "Are you sure want to unpublish this exam ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "get",
                                url: "{{ route('exam.unpublish') }}",
                                data: {
                                    id: id
                                },
                                dataType: "json",
                                beforeSend: function() {
                                    $('#overlayer').show();
                                },
                                success: function(response) {
                                    $('#overlayer').hide();
                                    var message = response.message;
                                    if (response.status) {
                                        window.location.reload();
                                    } else {
                                        toastr.error(message);
                                    }

                                }
                            });
                        }
                    });
            });
        });
    </script>
@endsection
