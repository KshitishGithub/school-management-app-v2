@extends('layouts.app')
@section('content')
@push('title')
        <title>Login user</title>
    @endpush
    <div class="login-right">
        <div class="login-right-wrap">
            <h1>Welcome to Dashbord</h1>
            <p class="account-subtitle">Enter your credential <a href="#"></a></p>
            <h2>Sign in</h2>
            <form action="{{ route('admin.login.post') }}" method="POST">
                @csrf
                @include('layouts.message')
                <div class="form-group">
                    <label>Email<span class="login-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}">
                    <span class="profile-views"><i class="fas fa-envelope"></i></span>
                    @error('email')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                    @enderror
                </div>
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
                <div class="forgotpass">
                    <div class="remember-me">
                        <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Remember me
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                        </label>
                    </div>
                    <a href="{{ route('admin.forgotpassword') }}">Forgot Password?</a>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block" type="submit">Login</button>
                </div>
            </form>
            {{-- <div class="login-or">
            <span class="or-line"></span>
            <span class="span-or">or</span>
        </div>
        <div class="social-login">
            <a href="#"><i class="fab fa-google-plus-g"></i></a>
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div> --}}
        </div>
    </div>
@endsection
