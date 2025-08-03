@extends('layouts.master')
@section('content')
    @push('title')
        <title>Users</title>
    @endpush
    @php
        define('PAGE', 'user_list');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        {{-- <h3 class="page-title">Users List</h3> --}}
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
                                    <div class="col">
                                        <h3 class="page-title">Users List</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('user.add') }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Add User"
                                         class="btn btn-primary">
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
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $key => $user)
                                            <tr>
                                                <td class="user_id">{{ ++$key }}</td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a class="avatar avatar-sm me-2">
                                                            <img class="avatar-img rounded-circle"src="{{ asset('uploads/images/user/' . $user->profile_image) }}"
                                                                alt="{{ $user->profile_image }}">
                                                        </a>
                                                    </h2>
                                                </td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->username }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>
                                                    <div class="">
                                                        @if ($user->role == '1')
                                                            <a class="badge badge-danger text-light">Teacher</a>
                                                        @elseif ($user->role == '2')
                                                            <a class="badge badge-success text-light">User</a>
                                                        @elseif ($user->role == '3')
                                                            <a class="badge badge-primary text-light">Admin</a>
                                                        @else
                                                            <a class="badge badge-info text-light">Super Admin</a>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="">
                                                        @if ($user->status === 'Active')
                                                            <a class="badge badge-primary text-success">{{ $user->status }}</a>
                                                        @elseif ($user->status === 'Inactive')
                                                            <a class="badge badge-warning text-danger">{{ $user->status }}</a>
                                                        @elseif ($user->status === 'Disable')
                                                            <a class="badge badge-success text-danger">{{ $user->status }}</a>
                                                        @else
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="{{ route('user.edit', $user->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Edit User"
                                                            class="btn btn-sm bg-danger-light text-info">
                                                            <i class="feather-edit me-1"></i>
                                                        </a>
                                                        <a class="btn btn-sm bg-danger-light" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Delete User"
                                                            data-user_id="{{ $user->id }}" id="user_delete_btn">
                                                            <i class="feather-trash-2 me-1 text-danger"></i>
                                                        </a>
                                                        {{-- @if (Session::get('role_name') === 'Super Admin')
                                                    @endif --}}
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
            // Delete User ........
            $(document).on("click", '#user_delete_btn', function(e) {
                e.preventDefault();
                var id = $(this).data("user_id");
                swal({
                        title: "Are you sure want to Delete this user ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "get",
                                url: "{{ route('user.destroy') }}",
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
                                        window.location.href = '{{ route('user.list') }}';
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
