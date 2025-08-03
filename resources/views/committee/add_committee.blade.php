@extends('layouts.master')
@section('content')
    @push('title')
        <title>Add Committee</title>
    @endpush
    @php
        define('PAGE', 'committee_add');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Add Committee</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add Committee</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('committee.store') }}" method="POST" id="CommitteeForm">
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
                                            <label>Phone Number <span class="login-danger">*</span></label>
                                            <input type="number" class="form-control" name="mobile">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Select Designation <span class="login-danger">*</span></label>
                                            <select class="form-control" name="designation">
                                                <option value="" selected>Choose designation</option>
                                                <option value="principal">Principal</option>
                                                <option value="vice_principal">Vice Principal</option>
                                                <option value="secretary">Secretary</option>
                                                <option value="president">President</option>
                                            </select>
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
                                            <label>Profile <span class="login-danger">*</span></label>
                                            <input type="file" class="form-control" name="photo" id="imageInput" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Signature <span class="login-danger">*</span></label>
                                            <input type="file" class="form-control" name="signature" id="" accept="image/*">
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
            $("#CommitteeForm").submit(function(e) {
                e.preventDefault();
                SubmitForm("CommitteeForm", CallBack);

                function CallBack(result) {
                    // console.log(result);
                    var message = result.message;
                    if (result.status) {
                        $("#CommitteeForm").trigger("reset");
                        $('#imagePreview').attr('src', '{{ url('assets/img/profiles/demo.png') }}');
                        toastr.success(message);
                    } else {
                        toastr.error(message);
                    }
                }
            });
        });
    </script>
@endsection
