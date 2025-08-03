@extends('layouts.master')
@section('content')
    @push('title')
        <title>Add Bus</title>
    @endpush
    @php
        define('PAGE', 'bus_add');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Add Bus</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Add Bus</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('bus.store') }}" method="POST" id="AddBusForm">
                                <div class="row">
                                    {{-- <div class="col-12">
                                        <h5 class="form-title"><span>Add User</span></h5>
                                    </div> --}}
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Bus Name <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="bus_name">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Bus Type <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="bus_type">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Bus Number <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="bus_number">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Total Seat <span class="login-danger">*</span></label>
                                            <input type="number" class="form-control" name="total_seat">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Drivar Name <span class="login-danger">*</span></label>
                                            <input type="text" class="form-control" name="driver_name">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Driver Mobile <span class="login-danger">*</span></label>
                                            <input type="text" data-inputmask='"mask": "9999999999"' data-mask
                                                class="form-control" name="driver_mobile">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Route <span class="login-danger">*</span></label>
                                            <select class="form-control select" name="route">
                                                <option value="" disabled selected>Select Route</option>
                                                @foreach ($routes as $id => $route)
                                                    <option value="{{ $route->id }}"> {{ $route->route }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group local-forms">
                                            <label>Bus Image <span class="login-danger">*</span></label>
                                            <input type="file" class="form-control" name="bus_photo" id="imageInput"
                                                accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <img class="img-fluid" id="imagePreview" src="{{ url('assets/img/bus.jpg') }}"
                                            alt="Image Preview" style="max-width: 100px">
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
            $("#AddBusForm").submit(function(e) {
                e.preventDefault();
                SubmitForm("AddBusForm", CallBack);

                function CallBack(result) {
                    // console.log(result);
                    var message = result.message;
                    if (result.status) {
                        window.location.href = "{{ route('bus.list') }}";
                    } else {
                        window.location.reload(true);
                    }
                }
            });
        });
    </script>
@endsection
