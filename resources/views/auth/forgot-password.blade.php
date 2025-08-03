@extends('layouts.app')
@section('content')
@push('title')
        <title>Forgot Password</title>
    @endpush
<div class="login-right">
    <div class="login-right-wrap">
        <h1>Reset Password</h1>
        <p class="account-subtitle">Let Us Help You</p>
        @include('layouts.message')
        <form action="{{ route('admin.send_link') }}" method="post">
            @csrf
            <div class="form-group">
                <label>Enter your registered email address <span
                        class="login-danger">*</span></label>
                <input class="form-control" name="email" type="email" @error('email') is-invalid @enderror value="{{ old('email') }}">
                <span class="profile-views"><i class="fas fa-envelope"></i></span>
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit">Reset My Password</button>
            </div>
            <div class="form-group mb-0">
                <a href="{{ route('admin.login')}}" class="btn btn-primary primary-reset btn-block">Login</a>
            </div>
        </form>

    </div>
</div>


@endsection
