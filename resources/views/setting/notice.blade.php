@extends('setting.layouts.main')
@section('setting_content')
    @push('title')
        <title>Notice Settings</title>
    @endpush
    @php
        define('INNER_PAGE', 'notice');
        define('PAGE_BREADCRUMB', 'Notice Setting');
    @endphp
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Notice</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="{{ route('admin.notice.add') }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Add Notice"
                                 class="btn btn-primary"><i
                                        class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="DataList"
                            class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                            <thead class="student-thread">
                                <tr>
                                    <th width="5">SL</th>
                                    <th>Title</th>
                                    <th width="10">Date</th>
                                    <th width="5">View</th>
                                    <th class="text-end" width="5">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($notices->isNotEmpty())
                                    @foreach ($notices as $key => $notice)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $notice->title }}</td>
                                            <td>{{ \Carbon\Carbon::parse($notice->created_at)->format('d-M-Y') }}</td>
                                            @if ($notice->link)
                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="{{$notice->link}}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Click here to go"
                                                             target="_blanck" class="btn btn-sm bg-danger-light"><i
                                                                class="feather-link me-1 text-primary"></i></a>
                                                    </div>
                                                </td>
                                            @elseif($notice->file)
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a href="{{ route('download.notice', ['file' => $notice->file]) }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Download Notice"
                                                         class="btn btn-sm bg-danger-light"><i
                                                            class="feather-arrow-down-circle me-1 text-success"></i></a>
                                                </div>
                                            </td>
                                            @else
                                            <td class="text-end">
                                                {{-- download file is not exits --}}
                                            </td>
                                            @endif
                                            <td class="text-end">
                                                <div class="actions">
                                                    <a data-notice_id="{{$notice->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Delete Notice"
                                                         class="btn btn-sm bg-danger-light" id="noticeDltBtn"><i
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
@endsection

@section('customJS')
    <script>
        $(document).ready(function() {

            // Delete .........

            $(document).on("click", "#noticeDltBtn", function(e) {
                e.preventDefault();
                var notice_id = $(this).data("notice_id");
                DeleteRecord(notice_id, "{{ route('notice.delete') }}", CallBack);

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
