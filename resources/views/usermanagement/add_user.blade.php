@extends('layouts.master')
@section('content')
    @push('title')
        <title>Add User</title>
    @endpush
    @php
        define('PAGE', 'user_add');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Add User</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add User</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('user.store') }}" method="POST" id="UserForm">
                                <div class="row">
                                    {{-- <div class="col-12">
                                        <h5 class="form-title"><span>Add User</span></h5>
                                    </div> --}}
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Name <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="name">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Email <span class="login-danger">*</span></label>
                                            <input type="email" class="form-control" name="email">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Username <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="username">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Phone Number <span class="login-danger">*</span></label>
                                            <input type="text" data-inputmask='"mask": "9999999999"' data-mask class="form-control" name="phone_number">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Status <span class="login-danger">*</span></label>
                                            <select class="form-control" name="status">
                                                <option disabled value="">Select Status</option>
                                                <option selected value="Active">Active</option>
                                                <option value="Disable">Disable</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Role Name <span class="login-danger">*</span></label>
                                            <select class="form-control" name="role_name">
                                                <option disabled value="">Select User Role</option>
                                                <option value="4">Super Admin</option>
                                                <option value="3">Admin</option>
                                                <option value="2">Normal User</option>
                                                <option value="1">Taecher</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Password <span class="login-danger">*</span></label>
                                            <input type="password" class="form-control" name="password">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Profile <span class="login-danger">*</span></label>
                                            <input type="file" class="form-control" name="profile_photo" id="imageInput"
                                                accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <img class="img-fluid" id="imagePreview"
                                            src="{{ url('assets/img/profiles/demo.png') }}" alt="Image Preview"
                                            style="max-width: 100px">
                                    </div>
                                    <div class="col-12 mt-3 text-center">
                                        <div class="student-submit">
                                            <button type="submit" class="btn btn-primary">Save</button>
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
            $("#UserForm").submit(function(e) {
                e.preventDefault();
                SubmitForm("UserForm", CallBack);

                function CallBack(result) {
                    // console.log(result);
                    var message = result.message;
                    if (result.status) {
                        window.location.href = "{{ route('user.list') }}";
                    } else {
                        window.location.reload(true);
                    }
                }
            });
        });
    </script>
@endsection
