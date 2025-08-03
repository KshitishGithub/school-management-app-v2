@extends('layouts.app')
@section('content')
    @push('title')
        <title>Update Password</title>
    @endpush
    <div class="login-right">
        <div class="login-right-wrap">
            <h1>Update Password</h1>
            <p class="account-subtitle">Let Us Help You</p>
            @include('layouts.message')
            <form action="{{ route('admin.update.password') }}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label>Password <span class="login-danger">*</span></label>
                    <input type="password" class="form-control pass-input @error('password') is-invalid @enderror"
                        name="password">
                    @error('password')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                    <span class="profile-views feather-eye toggle-password"></span>
                </div>
                <div class="form-group">
                    <label>Confirm Password <span class="login-danger">*</span></label>
                    <input type="password"
                        class="form-control pass-input @error('password_confirmation') is-invalid @enderror"
                        name="password_confirmation">
                    @error('password_confirmation')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror

                    <span class="profile-views feather-eye toggle-password"></span>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block" type="submit">Update Password</button>
                </div>
                <div class="form-group mb-0">
                    <a href="{{ route('admin.login') }}" class="btn btn-primary primary-reset btn-block">Login</a>
                </div>
            </form>

        </div>
    </div>
@endsection
