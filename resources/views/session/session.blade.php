@extends('layouts.master')
@section('content')
    @push('title')
        <title>Session</title>
    @endpush
    @php
        define('PAGE', 'session');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Session</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Session</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="row">
                                @if ($sessions->isEmpty())
                                    <p>No sessions found.</p>
                                @else
                                    <div class="col-xl-6">
                                        <div class="card flex-fill student-space comman-shadow">
                                            <div class="card-header d-flex align-items-center">
                                                <h5 class="card-title">Session List</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table star-student table-hover table-center table-borderless datatable table-striped">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>SL</th>
                                                                <th class="text-center">Session</th>
                                                                <th class="text-center">Active Session</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $i = 1; @endphp
                                                            @foreach ($sessions as $session)
                                                                <tr>
                                                                    <td class="text-nowrap">{{ $i++ }}</td>
                                                                    <td class="text-center">{{ $session->session }}</td>
                                                                    <td class="text-center">
                                                                        @if ($session->active)
                                                                            <span class="badge badge-success">Active</span>
                                                                        @else
                                                                            <span class="badge badge-danger">Deactive</span>
                                                                        @endif
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
                                            <h5 class="card-title">Add Session</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <form id="addSession" method="POST" action="{{ route('session.add') }}">
                                                    <div class="col-12">
                                                        <div class="form-group local-forms">
                                                            <label>Session <span class="login-danger">*</span></label>
                                                            <input type="text" name="session" class="form-control"
                                                                placeholder="Enter Session.....">
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
                                <div class="col-xl-3">
                                    <div class="card flex-fill student-space comman-shadow">
                                        <div class="card-header d-flex align-items-center">
                                            <h5 class="card-title">Change Session</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <form id="changeSessionForm" method="POST"
                                                    action="{{ route('session.change') }}">
                                                    <div class="col-12">
                                                        <select name="changeSession" id="changeSession"
                                                            class="form-control form-group local-forms">
                                                            <option value="" disabled selected>Change Session</option>
                                                            @foreach ($sessionslist as $id => $session)
                                                                @if ($session->active == 1)
                                                                    <option selected value="{{ $session->id }}">
                                                                        {{ $session->session }} </option>
                                                                @else
                                                                    <option value="{{ $session->id }}">
                                                                        {{ $session->session }} </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
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
            $("#addSession").submit(function(e) {
                e.preventDefault();
                SubmitForm("addSession", CallBack);

                function CallBack(result) {
                    // console.log(result);
                    var message = result.message;
                    if (result.status) {
                        $("#addSession").trigger("reset");
                        // toastr.success(message);
                        window.location.reload();
                    } else {
                        toastr.error(message);
                    }
                }
            });


            // Change Session......
            $("#changeSessionForm").submit(function(e) {
                e.preventDefault();
                SubmitForm("changeSessionForm", CallBack);

                function CallBack(result) {
                    // console.log(result);
                    var message = result.message;
                    if (result.status) {
                        // $("#changeSessionForm").trigger("reset");
                        // toastr.success(message);
                        window.location.reload();
                    } else {
                        toastr.error(message);
                    }
                }
            });
        });
    </script>
@endsection
