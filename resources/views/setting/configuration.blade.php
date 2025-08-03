@extends('setting.layouts.main')
@section('setting_content')
    @push('title')
        <title>
            Settings configuration</title>
    @endpush
    @php
        define('INNER_PAGE', 'configure');
        define('PAGE_BREADCRUMB', 'Setting Configuration');
    @endphp
    @if (Auth::user()->role == '4')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">School Basic Details</h5>
                    </div>
                    <div class="card-body">
                        <a href="/migrate" class="m-2 btn btn-primary">Migration</a>
                        <a href="/clear-route-cache" class="m-2 btn btn-secondary">Route Clear</a>
                        <a href="/clear-config-cache" class="m-2 btn btn-success">Config Clear</a>
                        <a href="/clear-application-cache" class="m-2 btn btn-danger">Cache Clear</a>
                        <a href="/storage-link" class="m-2 btn btn-warning">Storage Link</a>
                        <a href="/run-scheduler" class="m-2 btn btn-secondary">Schedule Run</a>
                        {{-- <a href="/route-cache" class="m-2 btn btn-dark">Route Cache</a> --}}
                        {{-- <a href="/optimize" class="m-2 btn btn-success">Optimize</a> --}}
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
