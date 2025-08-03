@extends('layouts.error')
@section('content')
    <div class="main-wrapper">
        <div class="error-box">
            <h1 class="text-dager">400</h1>
            <h3 class="h2 mb-3"><i class="fas fa-exclamation-triangle"></i> Invalid request!</h3>
            <p class="h4 font-weight-normal">Invalid or expired the page.</p>
            <a href="/dashboard" class="btn btn-primary">Back to Home</a>
        </div>
    </div>
@endsection
