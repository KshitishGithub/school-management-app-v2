@extends('layouts.master')
@section('content')
@push('title')
<title>Library</title>
@endpush
@php
define('PAGE', 'library');
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
                        <li class="breadcrumb-item active">Library</li>
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
                                    <h3 class="page-title">Library</h3>
                                </div>
                                <div class="col-auto text-end float-end ms-auto download-grp">
                                    {{-- <a href="#" class="btn btn-outline-primary me-2"><i
                                            class="fas fa-download"></i>
                                        Download</a> --}}
                                    <a href="{{ route('library.create') }}" class="btn btn-primary"><i
                                            class="fas fa-plus"></i> Add Book</a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table border-0 star-student table-hover table-center mb-0 table-striped">
                                <thead class="student-thread">
                                    <tr>
                                        <th>Sl</th>
                                        <th>Book Name</th>
                                        <th>Class</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Type</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($libraries as $key => $library)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $library->book_name }}</td>
                                            <td>{{ $library->class }}</td>
                                            <td>{{ $library->quantity }}</td>
                                            <td>{{ $library->price }}</td>
                                            <td>
                                                {{ $library->type == 1 ? 'Book' : 'Other' }}
                                            </td>
                                            <td>
                                                @if ($library->quantity > 0)
                                                    <span class="badge badge-success">In Stock</span>
                                                @else
                                                    <span class="badge badge-danger">Out of Stock</span>
                                                @endif
                                            </td>
                                                <td>

                                                @if ($library->status == '1')
                                                    <span class="badge badge-primary">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Deactive</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a href="{{ route('library.edit', $library->id) }}" class="btn btn-sm bg-danger-light">
                                                        <i class="feather-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">{{ $libraries->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @endsection
    @section('customJS')
    @endsection
