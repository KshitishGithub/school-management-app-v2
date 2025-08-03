@extends('layouts.master')
@section('content')
@push('title')
<title>Library</title>
@endpush
@php
define('PAGE', 'details');
@endphp
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Library</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Sales Details</li>
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
                                    <h3 class="page-title">Sales Details</h3>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th with="5%">Sl</th>
                                        <th with="20%">Class</th>
                                        <th>Name</th>
                                        <th>Roll No</th>
                                        <th with="5%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesDetails as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $data->class }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->roll_no }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-success stock_details"
                                                data-id="{{ $data->id }}"><i class="feather-eye"></i></button>
                                        </td>
                                        @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">{{ $salesDetails->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalTop" tabindex="-1" role="dialog" aria-labelledby="modalTopLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalTopLabel"><span><b>Sales details:</b></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-info table-hovered">
                    <thead class="text-center">
                        <th width="2%">Sl</th>
                        <th>Book Name</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Date-Time</th>
                    </thead>
                    <tbody class="text-center" id="table_data">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('customJS')
    <script>
        $(document).ready(function(){
            // get the sales details
            $(document).on('click', '.stock_details', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                {{--  alert(id);  --}}
                $.ajax({
                    type: "get",
                    url: "{{ route('sell.details') }}",
                    data: {
                        id: id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#overlayer').show();
                    },
                    success: function(response) {
                        $('#overlayer').hide();
                        if (response.status) {
                            $('#table_data').html('');
                            $('#table_data').append(response.data);
                            $('#modalTop').modal('show');
                        } else {
                            alert("No data available");
                        }

                    }
                });
            })
        })
    </script>
@endsection
