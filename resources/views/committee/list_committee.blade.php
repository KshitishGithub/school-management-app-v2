@extends('layouts.master')
@section('content')
    @push('title')
        <title>Committees</title>
    @endpush
    @php
        define('PAGE', 'committee_list');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Users List</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Users List</li>
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
                                    {{-- <div class="col">
                                        <h3 class="page-title">Users List</h3>
                                    </div> --}}
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('committee.create') }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Add Committee" class="btn btn-primary">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th>SL</th>
                                            <th>Profile</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Designation</th>
                                            <th>Status</th>
                                            <th>Signature</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($committees as $key => $committee)
                                            <tr>
                                                <td class="committee_id">{{ ++$key }}</td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle"src="{{ asset('uploads/images/committee/' . $committee->photo) }}"
                                                                alt="{{ $committee->photo }}">
                                                        </a>
                                                    </h2>
                                                </td>
                                                <td>{{ $committee->name }}</td>
                                                <td>{{ $committee->email }}</td>
                                                <td>{{ $committee->mobile }}</td>
                                                <td>{{ $committee->designation }}</td>
                                                <td>
                                                    <div class="">
                                                        @if ($committee->status === 'Active')
                                                            <a
                                                                class="badge badge-primary text-success">{{ $committee->status }}</a>
                                                        @elseif ($committee->status === 'Inactive')
                                                            <a
                                                                class="badge badge-warning text-danger">{{ $committee->status }}</a>
                                                        @elseif ($committee->status === 'Disable')
                                                            <a
                                                                class="badge badge-success text-danger">{{ $committee->status }}</a>
                                                        @else
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <img class="w-50"src="{{ asset('uploads/images/committee/' . $committee->signature) }}"
                                                                alt="{{ $committee->signature }}">
                                                </td>
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a class="btn btn-sm bg-danger-light" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Delete Commitees"
                                                            data-committee_id="{{ $committee->id }}"
                                                            id="committee_delete_btn">
                                                            <i class="feather-trash-2 me-1 text-danger"></i>
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
@endsection
@section('customJS')
    <script>
        $(document).ready(function() {
            // Delete committee ........
            $(document).on("click", '#committee_delete_btn', function(e) {
                e.preventDefault();
                var id = $(this).data("committee_id");
                var url = "{{ route('committee.destroy', ['committee' => ':id']) }}";
                url = url.replace(':id', id);

                swal({
                        title: "Are you sure want to Delete this committee?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "delete",
                                url: url,
                                dataType: "json",
                                beforeSend: function() {
                                    $('#overlayer').show();
                                },
                                success: function(response) {
                                    $('#overlayer').hide();
                                    // console.log(response);
                                    var message = response.message;
                                    if (response.status) {
                                        toastr.success(message);
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
