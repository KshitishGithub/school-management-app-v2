@extends('layouts.master')
@section('content')
    @push('title')
        <title>Buses</title>
    @endpush
    @php
        define('PAGE', 'bus_list');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        {{-- <h3 class="page-title">Users List</h3> --}}
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Bus List</li>
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
                                        <h3 class="page-title">Buses List</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('bus.add') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                            title data-bs-original-title="Add User" class="btn btn-primary">
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
                                            <th>Bus Name</th>
                                            <th>Bus Type</th>
                                            <th>Bus Number</th>
                                            <th>Driver Name</th>
                                            <th>Driver Number</th>
                                            <th>Route</th>
                                            {{-- <th>Bus Photo</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($buses as $key => $bus)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $bus->bus_name }}</td>
                                                <td>{{ $bus->bus_type }}</td>
                                                <td>{{ $bus->bus_number }}</td>
                                                <td>{{ $bus->driver_name }}</td>
                                                <td>{{ $bus->driver_mobile }}</td>
                                                <td>{{ $bus->route }}</td>
                                                {{-- <td>
                                                    <h2 class="table-avatar">
                                                        <a class="avatar avatar-sm me-2">
                                                            <img class="avatar-img" src="{{ Storage::url('images/bus/' . $bus->bus_photo) }}" alt="{{ $bus->bus_photo }}">
                                                        </a>
                                                    </h2>
                                                </td> --}}
                                                <td class="text-center">
                                                    <div class="actions">
                                                        {{-- <a href="{{ route('bus.edit', $bus->id) }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Edit bus"
                                                            class="btn btn-sm bg-danger-light text-info">
                                                            <i class="feather-edit me-1"></i>
                                                        </a> --}}
                                                        <a class="btn btn-sm bg-danger-light" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title
                                                            data-bs-original-title="Delete bus"
                                                            data-bus_id="{{ $bus->id }}" id="bus_delete_btn">
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
            $(document).on("click", '#bus_delete_btn', function(e) {
                e.preventDefault();
                var id = $(this).data("bus_id");
                swal({
                        title: "Are you sure want to Delete this bus ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "get",
                                url: "{{ route('bus.destroy') }}",
                                data: {
                                    id: id
                                },
                                dataType: "json",
                                beforeSend: function() {
                                    $('#overlayer').show();
                                },
                                success: function(response) {
                                console.log(response);
                                    $('#overlayer').hide();
                                    var message = response.message;
                                    if (response.status) {
                                        window.location.reload(true);
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
