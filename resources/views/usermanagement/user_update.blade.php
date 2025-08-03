@extends('layouts.master')
@section('content')
    @push('title')
        <title>Edit User</title>
    @endpush
    @php
        define('PAGE', 'user_edit');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Edit User</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Edit User</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('user.update') }}" method="POST" id="UserUpdate">
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Name <span class="login-danger">*</span></label>
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <input type="text" class="form-control" name="name"
                                                value="{{ $user->name }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Email <span class="login-danger">*</span></label>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ $user->email }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Username <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="username"
                                                value="{{ $user->username }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Phone Number <span class="login-danger">*</span></label>
                                            <input type="number" class="form-control" name="phone_number"
                                                value="{{ $user->phone }}">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Status <span class="login-danger">*</span></label>
                                            <select class="form-control" name="status">
                                                <option {{ $user->status == 'Active' ? 'selected' : '' }} value="Active">
                                                    Active</option>
                                                <option {{ $user->status == 'Disable' ? 'selected' : '' }} value="Disable">
                                                    Disable</option>
                                                <option {{ $user->status == 'Inactive' ? 'selected' : '' }}
                                                    value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Role Name <span class="login-danger">*</span></label>
                                            <select class="form-control" name="role_name">
                                                <option {{ $user->role === '3' ? 'selected' : '' }} value="3">Admin
                                                </option>
                                                <option {{ $user->role === '4' ? 'selected' : '' }} value="4">Super
                                                    Admin</option>
                                                <option {{ $user->role === '2' ? 'selected' : '' }} value="2">Normal
                                                    User</option>
                                                <option {{ $user->role === '1' ? 'selected' : '' }} value="1">Taecher
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Profile <span class="login-danger">*</span></label>
                                            <input type="file" class="form-control" optional='true' name="profile_photo"
                                                id="imageInput" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <img class="img-fluid" id="imagePreview"
                                            src="{{ asset('uploads/images/user/' . $user->profile_image) }}"
                                            alt="Image Preview" style="max-width: 100px">
                                    </div>
                                    <div class="col-12">
                                        <div class="student-submit">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
            // Submit Form
            $("#UserUpdate").submit(function(e) {
                e.preventDefault();
                SubmitForm("UserUpdate", CallBack);

                function CallBack(result) {
                    // console.log(result);
                    var message = result.message;
                    if (result.status) {
                        window.location.href = '{{ route('user.list') }}';
                    } else {
                        toastr.error(message);
                    }
                }
            });
        });
    </script>
@endsection
