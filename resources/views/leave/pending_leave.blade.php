@extends('layouts.master')
@section('content')
    @push('title')
        <title>Pending Leave List</title>
    @endpush
    @php
        define('PAGE', 'pending_leave');
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
                                        <h3 class="page-title">Pending Leave List</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('leave.approved') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title data-bs-original-title="Approved Leave"
                                            class="btn btn-primary"><i class="bi bi-list-check"></i></a>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('leave.reject') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title data-bs-original-title="Rejected Leave"
                                            class="btn btn-secondary"><i class="bi bi-list-check"></i></a>
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
                                            <th>Letter</th>
                                            <th>Status</th>
                                            <th width="5">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($pendingLeaves->isNotEmpty())
                                            @foreach ($pendingLeaves as $key => $leave)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $leave->name }}</td>
                                                    <td>{{ $leave->class }}</td>
                                                    <td>{{ $leave->section ?? 'N/A' }}</td>
                                                    <td>{{ $leave->roll }}</td>
                                                    <td>{{ $leave->reasons }}</td>
                                                    <td>{{ $leave->to_date }}</td>
                                                    <td>{{ $leave->from_date }}</td>
                                                    <td>
                                                        <a target="_blank" href="{{ asset('uploads/images/leave/' . $leave->letterName) }}">
                                                            <img src="{{ asset('uploads/images/leave/' . $leave->letterName) }}" alt="" width="50" srcset="">
                                                        </a>
                                                    </td>

                                                    <td><span class="badge badge-primary">Pending</span></td>
                                                    <td class="d-flex">
                                                        <div class="actions">
                                                            <a data-leave_id="{{ $leave->id }}" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title
                                                                data-bs-original-title="Approve"
                                                                class="btn btn-sm bg-success" id="approveLeaveBtn"><i
                                                                    class="bi bi-check-lg text-light"></i></a>
                                                        </div>
                                                        <div class="actions mx-2">
                                                            <a data-leave_id="{{ $leave->id }}" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title
                                                                data-bs-original-title="Reject"
                                                                class="btn btn-sm bg-warning" id="rejectLeaveBtn"><i
                                                                    class="bi bi-x  text-light"></i></a>
                                                        </div>
                                                        <div class="actions">
                                                            <a data-leave_id="{{ $leave->id }}" data-bs-toggle="tooltip"
                                                                data-bs-placement="top" title
                                                                data-bs-original-title="Delete" class="btn btn-sm bg-danger"
                                                                id="deleteLeaveBtn"><i
                                                                    class="feather-trash-2 me-1 text-light"></i></a>
                                                        </div>
                                                    </td>
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

@section('customJS')
    <script>
        $(document).ready(function() {

            // Delete .........

            $(document).on("click", "#deleteLeaveBtn", function(e) {
                e.preventDefault();
                var leave_id = $(this).data("leave_id");
                DeleteRecord(leave_id, "{{ route('leave.delete') }}", CallBack);

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

            // Approve the leave --------------------------------
            $(document).on('click', '#approveLeaveBtn', function(e) {
                e.preventDefault();
                var leave_id = $(this).data('leave_id');
                swal({
                        title: "Are you sure?",
                        text: "Want to approve this leave ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willAdmit) => {
                        if (willAdmit) {
                            $.ajax({
                                type: "post",
                                url: "{{ route('leave.approve') }}",
                                data: {
                                    id: leave_id
                                },
                                beforeSend: function() {
                                    $('#overlayer').show();
                                },
                                dataType: "json",
                                success: function(response) {
                                    console.log(response);
                                    $('#overlayer').hide();
                                    var message = response.message;
                                    if (response.status) {
                                        swal("Good job!", message, "success")
                                            .then((value) => {
                                                window.location.reload();
                                            })
                                    } else {
                                        toastr.error(message);
                                    }
                                }
                            });
                        }
                    });
            });
            // Reject the leave --------------------------------
            $(document).on('click', '#rejectLeaveBtn', function(e) {
                e.preventDefault();
                var leave_id = $(this).data('leave_id');
                swal({
                        title: "Are you sure?",
                        text: "Want to reject this leave ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willAdmit) => {
                        if (willAdmit) {
                            $.ajax({
                                type: "post",
                                url: "{{ route('leave.rejectLeave') }}",
                                data: {
                                    id: leave_id
                                },
                                beforeSend: function() {
                                    $('#overlayer').show();
                                },
                                dataType: "json",
                                success: function(response) {
                                    console.log(response);
                                    $('#overlayer').hide();
                                    var message = response.message;
                                    if (response.status) {
                                        swal("Good job!", message, "success")
                                            .then((value) => {
                                                window.location.reload();
                                            })
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
