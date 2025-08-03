@extends('layouts.master')
@section('content')
    @push('title')
        <title>Changed Password</title>
    @endpush
    @php
        define('PAGE', 'Change Password');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <div class="login-right">
                                            <div class="login-right-wrap">
                                                <p class="account-subtitle">Change your password <a href="javascript:;  "></a></p>
                                                <form action="{{ route('admin.password_change') }}" method="POST">
                                                    @csrf
                                                    @include('layouts.message')
                                                    <div class="form-group">
                                                        <label>Old Password<span class="login-danger">*</span></label>
                                                        <input type="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            name="old_password" value="{{ old('old_password') }}">
                                                        @error('old_password')
                                                            <small class="text-danger">
                                                                {{ $message }}
                                                            </small>
                                                        @enderror
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Password<span class="login-danger">*</span></label>
                                                        <input type="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            name="password">
                                                        @error('password')
                                                            <small class="text-danger">
                                                                {{ $message }}
                                                            </small>
                                                        @enderror
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
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-primary btn-block"
                                                            type="submit">Change password</button>
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

{{-- @section('customJS')
    <script>
        $(document).ready(function() {

            // Delete Class.........

            $(document).on("click", "#classDltBtn", function(e) {
                e.preventDefault();
                var class_id = $(this).data("class_id");
                DeleteRecord(class_id, "{{ route('class.delete') }}", CallBack);

                function CallBack(result) {
                    $('#overlayer').hide();
                    console.log(result);
                    if (result.status == true) {
                        swal("Good job!", result.message, "success")
                            .then((value) => {
                                window.location.reload();
                            })
                    } else {
                        var message = result.message;
                        toastr.error(message);
                    }
                }
            });
        });
    </script>
@endsection --}}
