@extends('layouts.master')
@section('content')
    @push('title')
        <title>Route</title>
    @endpush
    @php
        define('PAGE', 'route');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Route</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Route</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="row">
                                @if ($routes->isEmpty())
                                    {{-- <p>No routes found.</p> --}}
                                @else
                                    <div class="col-xl-6">
                                        <div class="card flex-fill student-space comman-shadow">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="card-title">Route List</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table star-student table-hover table-center table-borderless datatable table-striped">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>SL</th>
                                                                <th class="text-center">Route</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $i = 1; @endphp
                                                            @foreach ($routes as $route)
                                                                <tr>
                                                                    <td class="text-nowrap">{{ $i++ }}</td>
                                                                    <td class="text-center">{{ $route->route }}</td>
                                                                    <td class="text-center">
                                                                        <a class="btn btn-sm bg-danger-light"
                                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                                            title data-bs-original-title="Delete route"
                                                                            data-route_id="{{ $route->id }}"
                                                                            id="route_delete_btn">
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
                                <div class="col-xl-6">
                                    <div class="card flex-fill student-space comman-shadow">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="card-title">Add Route</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <form id="AddRoute" method="POST" action="{{ route('route.add') }}">
                                                    <div class="col-12">
                                                        <div class="form-group local-forms">
                                                            <label>Route <span class="login-danger">*</span></label>
                                                            <input type="text" name="route" class="form-control"
                                                                placeholder="Enter Route Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
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
            $(document).on("click", '#route_delete_btn', function(e) {
                e.preventDefault();
                var id = $(this).data("route_id");
                swal({
                        title: "Are you sure want to Delete this route , bus and bus stops ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                type: "get",
                                url: "{{ route('route.destroy') }}",
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
