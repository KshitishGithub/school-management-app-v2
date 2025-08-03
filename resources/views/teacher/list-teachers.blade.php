@extends('layouts.master')
@section('content')
    @push('title')
        <title>All Teachers</title>
    @endpush
    @php
        define('PAGE', 'teacher_list');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Teachers</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Teachers</li>
                        </ul>
                    </div>
                </div>
            </div>
            @include('layouts.message')
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">Teachers</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('teacher.create') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title data-bs-original-title="Add Teacher"
                                            class="btn btn-primary"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="DataList"
                                    class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Qualification</th>
                                            <th>Experience</th>
                                            <th>Mobile Number</th>
                                            <th>Email</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teachers as $key => $teacher)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a href="javascript:;" class="avatar avatar-sm me-2">
                                                            @if (!empty($teacher->image))
                                                                <img class="avatar-img rounded-circle"
                                                                    src="{{ asset('uploads/images/teachers/' . $teacher->image) }}"
                                                                    alt="{{ $teacher->image }}">
                                                            @else
                                                                <img class="avatar-img rounded-circle"
                                                                    src="{{ URL::to('assets/img/profiles/demo.png') }}"
                                                                    alt="">
                                                            @endif
                                                        </a>
                                                        <a href="javascript:;">{{ $teacher->name }}</a>
                                                    </h2>
                                                </td>
                                                <td>{{ $teacher->gender }}</td>
                                                <td>{{ $teacher->qualification }}</td>
                                                <td>{{ $teacher->experience }}</td>
                                                <td>{{ $teacher->mobile }}</td>
                                                <td>{{ $teacher->email }}</td>
                                                <td class="text-center">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="true"><i
                                                                class="fas fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-end"
                                                            data-popper-placement="bottom-end"
                                                            style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(0px, 22px, 0px);">
                                                            <a class="dropdown-item" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Teachers Profile"
                                                                href="{{ route('teacher.profile', ['id' => $teacher->id]) }}"><i
                                                                    class="fa fa-user  me-2"
                                                                    aria-hidden="true"></i>Profile</a>
                                                            <a class="dropdown-item" href="javascript:;" id="teacher_delete" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Delete Teachers"
                                                                data-teacher_id="{{ $teacher->id }}"> <i
                                                                    class="fa fa-trash me-2"
                                                                    aria-hidden="true"></i>Delete</a>
                                                        </div>
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
@endsection
@section('customJS')
    <script>
        $(document).ready(function() {
            $(document).on("click", "#teacher_delete", function(e) {
                e.preventDefault();
                var teacher_id = $(this).data("teacher_id");
                DeleteRecord(teacher_id, "{{ route('teacher.delete') }}", CallBack);

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
