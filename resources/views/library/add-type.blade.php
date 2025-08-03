@extends('layouts.master')
@section('content')
@push('title')
<title>Add Type</title>
@endpush
@php
define('PAGE', 'library');
@endphp
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Add Books Types</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('library.index') }}">Library</a></li>
                        <li class="breadcrumb-item active">Add Books Type</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('library.store.type') }}" method="POST" id="store_type">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="form-title"><span>Book Type</span></h5>
                                </div>
                                <div class="col-12">
                                    <div class="form-group local-forms">
                                        <label>Book Type <span class="login-danger">*</span></label>
                                        <input type="text" name="book_type" class="form-control">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            <table class="table table-bordered table-stripe table-hovere">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($book_type as $row)
                        <tr>
                            <td scope="row">{{ $loop->iteration }}</td>
                            <td>{{ $row->type }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('customJS')
    <script>
        // Submit Form
        $("#store_type").submit(function(e) {
            e.preventDefault();
            SubmitForm("store_type", CallBack);

            function CallBack(result) {
                // console.log(result);
                var message = result.message;
                if (result.status == true) {
                    $("#store_type").trigger("reset");
                    {{--  toastr.success(message);  --}}
                    window.location.reload(true);
                } else {
                    toastr.error(message);
                }
            }
        });
    </script>
@endsection
