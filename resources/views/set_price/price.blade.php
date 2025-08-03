@extends('layouts.master')
@section('content')
@push('title')
<title>Price</title>
@endpush
@php
define('PAGE', 'fees_type');
@endphp
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Price Table</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                        <li class="breadcrumb-item active">Price Table</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="row">
                            @if ($prices->isEmpty())
                            <p>No sessions found.</p>
                            @else
                            <div class="col-xl-6">
                                <div class="card flex-fill student-space comman-shadow">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="card-title">Price List</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table
                                                class="table star-student table-hover table-center table-borderless datatable table-striped">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>SL</th>
                                                        <th class="text-center">Class</th>
                                                        <th class="text-center">Price Type</th>
                                                        <th class="text-center">Price</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $i = 1; @endphp
                                                    @foreach ($prices as $session)
                                                    <tr>
                                                        <td class="text-nowrap">{{ $i++ }}</td>
                                                        <td class="text-center">{{ $session->class }}</td>
                                                        <td class="text-center">{{ $session->price_type }}</td>
                                                        <td class="text-center">{{ $session->prices }}</td>
                                                        <td class="text-center">
                                                            <a href="#" data-id="{{ $session->id }}" class="delete_type btn btn-danger btn-sm text-light">Delete</a>
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
                            <div class="col-xl-3">
                                <div class="card flex-fill student-space comman-shadow">
                                    <div class="card-header d-flex align-items-center">
                                        <h5 class="card-title">Add Price</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <form id="AddPrice" method="POST" action="{{ route('fees_type.store') }}">
                                                <div class="col-12">
                                                    <div class="form-group local-forms">
                                                        <label>Class <span class="login-danger">*</span></label>
                                                        <select name="class_id" id="class-select" class="form-control">
                                                            <option value="" disabled selected>Select a class</option>
                                                            @foreach($classes as $class)
                                                            <option value="{{ $class->id }}">{{ $class->class }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group local-forms">
                                                        <label>Fees Type <span class="login-danger">*</span></label>
                                                        <select name="price_type" class="form-control">
                                                            <option value="" disabled selected>Select price type</option>
                                                            <option value="School Fees">School Fees</option>
                                                            <option value="Mess Fees">Mess Fees</option>
                                                            <option value="Transport Fees">Transport Fees</option>
                                                            <option value="Hostel Fees">Hostel Fees</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group local-forms">
                                                        <label>Price <span class="login-danger">*</span></label>
                                                        <input type="text" name="prices" class="form-control"
                                                            placeholder="Enter Price">
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
            $("#AddPrice").submit(function(e) {
                e.preventDefault();
                SubmitForm("AddPrice", CallBack);

                function CallBack(result) {
                    console.log(result);
                    var message = result.message;
                    if (result.status) {
                        $("#AddPrice").trigger("reset");
                        // toastr.success(message);
                        window.location.reload();
                    } else {
                        toastr.error(message);
                    }
                }
            });


            // Delete the Price
            $(document).on('click','.delete_type',function(e){
                e.preventDefault();
                var id = $(this).data('id');
                var url = "{{ route('fees_type.delete', ':id') }}".replace(':id', id);
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function(response) {

                                  if(response.status){
                                      window.location.reload();
                                  }else{
                                    Swal.fire(
                                    'Error!',
                                    'Something was wrong.',
                                   'error'
                                )
                                  }
                            }
                        });
                    }
                });
            })
        });
</script>
@endsection
