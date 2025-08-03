@extends('layouts.master')
@section('content')
    @push('title')
        <title>Class List</title>
    @endpush
    @php
        define('PAGE', 'class_list');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Classes</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item active">Classes</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    {{-- @include('layouts.message') --}}
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">Classes List</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('class.add') }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Add Class"
                                         class="btn btn-primary"><i
                                                class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table
                                    class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                    <thead class="student-thread">
                                        <tr>
                                            <th>SL</th>
                                            {{--  <th>Session</th>  --}}
                                            <th>Class</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($classes->isNotEmpty())
                                            @foreach ($classes as $key => $class)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    {{--  <td>
                                                        <a>{{ $class->session }}</a>
                                                    </td>  --}}
                                                    <td>
                                                        <a>{{ $class->class }}</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
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
