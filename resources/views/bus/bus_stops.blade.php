@extends('layouts.master')
@section('content')
    @push('title')
        <title>Bus Stops</title>
    @endpush
    @php
        define('PAGE', 'bus_stop');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Bus Stops</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Bus Stops</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="row">
                                @if ($stops->isEmpty())
                                    {{-- <p>No routes found.</p> --}}
                                @else
                                    <div class="col-xl-8">
                                        <div class="card flex-fill student-space comman-shadow">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="card-title">Stops List</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table star-student table-hover table-center table-borderless datatable table-striped">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th width="5%">SL</th>
                                                                <th class="text-center">Routes</th>
                                                                <th class="text-center">Stops</th>
                                                                <th class="text-center">Stops SL</th>
                                                                <th class="text-center">Arrival Time</th>
                                                                <th class="text-center">Left Time</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $i = 1; @endphp
                                                            @foreach ($stops as $stop)
                                                                <tr>
                                                                    <td class="text-nowrap">{{ $i++ }}</td>
                                                                    <td class="text-center">{{ $stop->route }}</td>
                                                                    <td class="text-center">{{ $stop->bus_stops }}</td>
                                                                    <td class="text-center">{{ $stop->stops_sl }}</td>
                                                                    <td class="text-center">{{ $stop->arrival_time }}</td>
                                                                    <td class="text-center">{{ $stop->left_time }}</td>
                                                                    <td class="text-center">
                                                                        <a class="btn btn-sm bg-danger-light"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            title data-bs-original-title="Delete stops"
                                                                            data-stop_id="{{ $stop->id }}"
                                                                            id="stops_delete_btn">
                                                                            <i class="feather-trash-2 me-1 text-danger"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xl-4">
                                    <div class="card flex-fill student-space comman-shadow">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="card-title">Add Bus Stops</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <form id="AddRoute" method="POST" action="{{ route('bus_stop.add') }}">
                                                    <div class="col-3 col-sm-12">
                                                        <div class="form-group local-forms">
                                                            <label>Route <span class="login-danger">*</span></label>
                                                            <select class="form-control select" name="route">
                                                                <option value="" disabled selected>Select Route
                                                                </option>
                                                                @foreach ($routes as $id => $route)
                                                                    <option value="{{ $route->id }}"> {{ $route->route }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group local-forms">
                                                            <label>Bus Stops <span class="login-danger">*</span></label>
                                                            <input type="text" name="bus_stops" class="form-control"
                                                                placeholder="Enter Bus Stops">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group local-forms">
                                                            <label>Stops SL <span class="login-danger">*</span></label>
                                                            <input type="text" name="stops_sl" class="form-control"
                                                                placeholder="Enter Stops SL">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group local-forms">
                                                            <label>Arrival Time <span class="login-danger">*</span></label>
                                                            <input type="time" name="arrival_time" class="form-control"
                                                                placeholder="Arrival time">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group local-forms">
                                                            <label>Left Time <span class="login-danger">*</span></label>
                                                            <input type="time" name="left_time" class="form-control"
                                                                placeholder="Left time">
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-12">
                                            <div class="student-submit">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            // Submit Form
            $("#AddRoute").submit(function(e) {
                e.preventDefault();
                SubmitForm("AddRoute", CallBack);

                function CallBack(result) {
                    // console.log(result);
                    var message = result.message;
                    if (result.status) {
                        window.location.reload(true);
                    } else {
                        toastr.error(message);
                    }
                }
            });


            // Delete Route
            $(document).on("click", '#stops_delete_btn', function(e) {
                e.preventDefault();
                var id = $(this).data("stop_id");
                swal({
                        title: "Are you sure want to Delete this stops ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "get",
                                url: "{{ route('bus_stop.destroy') }}",
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
