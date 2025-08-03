@extends('layouts.master')
@section('content')
    @push('title')
        <title>Holiday List</title>
    @endpush
    @php
        define('PAGE', 'holiday_list');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">Holiday List</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('holiday.add') }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Add new holiday"
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
                                            <th width="5">SL</th>
                                            <th>Holiday Name</th>
                                            <th>Day</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th width="5">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($holidays->isNotEmpty())
                                            @foreach ($holidays as $key => $holiday)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $holiday->holiday }}</td>
                                                    <td>{{ $holiday->day }}</td>
                                                    <td>{{ $holiday->start_date }}</td>
                                                    <td>{{ $holiday->end_date }}</td>
                                                    <td class="text-end">
                                                        <div class="actions">
                                                            <a data-holiday_id="{{$holiday->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Delete Holidays"
                                                                 class="btn btn-sm bg-danger-light" id="holidayDltBtn"><i
                                                                    class="feather-trash-2 me-1 text-danger"></i></a>
                                                        </div>
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

@section('customJS')
    <script>
        $(document).ready(function() {

            // Delete .........

            $(document).on("click", "#holidayDltBtn", function(e) {
                e.preventDefault();
                var holiday_id = $(this).data("holiday_id");
                DeleteRecord(holiday_id, "{{ route('holiday.delete') }}", CallBack);

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
@endsection
