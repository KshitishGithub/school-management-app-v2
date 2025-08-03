@extends('setting.layouts.main')
@section('setting_content')
    @push('title')
        <title>Banner Add</title>
    @endpush
    @php
        define('INNER_PAGE', 'notice');
        define('PAGE_BREADCRUMB', 'Banner Setting');
    @endphp

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Add Notice</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="{{ route('admin.notice') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body pt-0">
                            <form action="{{ route('admin.notice.store') }}" method="POST" id="noticeForm">
                                @csrf
                                <div class="settings-form">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" class="form-control" optional="true" placeholder="Notice title"
                                            name="title">
                                    </div>
                                    <div class="form-group">
                                        <div class="icheck-danger d-inline">
                                            <input type="checkbox" class="form-check-input" id="FileCheckBox" checked>
                                            <label for="FileCheckBox">
                                                Attatch File
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group" id="notice_link">
                                        <label>Notice link <span class="star-red">*</span></label>
                                        <input type="text" class="form-control" optional="true" placeholder="Notice link" name="notice_link">
                                    </div>
                                    <div class="form-group" id="notice_file">
                                        <p class="settings-label">Notice file</p>
                                        <input type="file" optional="true" name="notice_file" class="form-control">
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="settings-btns">
                                            <button type="submit" class="btn btn-orange">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('customJS')
    <script>
        // Notice File Hide Show////
        $(function() {
            $("#notice_link").hide();
            checkBox = document.getElementById('FileCheckBox').addEventListener('click', event => {
                if (event.target.checked) {
                    $("#notice_file").show();
                    $("#notice_link").hide();
                } else {
                    $("#notice_file").hide();
                    $("#notice_link").show();
                }
            });
        })


        // Submit Form
        $("#noticeForm").submit(function(e) {
            e.preventDefault();
            SubmitForm("noticeForm", CallBack);

            function CallBack(result) {
                // console.log(result);
                var message = result.message;
                if (result.status) {
                    $("#noticeForm").trigger("reset");
                    toastr.success(message);
                } else {
                    toastr.error(message);
                }
            }
        });
    </script>
@endsection
{{--
$("#NoticeForm").on("submit", function(e) {
    e.preventDefault();
    var title = $("#title").val();
    var link = $("#link").val();
    var notice_file = $('input[type=file]').val().split('\\').pop();
    if (title == "") {
        toastr.error('Notice Title is required.');
    } else if (link == "" && $("#FileCheckBox").prop('checked') == false) {
        toastr.error('Notice link is required.');
    } else if (notice_file == "" && $("#FileCheckBox").prop('checked') == true) {
        toastr.error('Media file is required.');
    } else {
        var data = new FormData(this);
        $.ajax({
            url: "php_files/add_notice.php",
            type: "POST",
            data: data,
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#overlayer').show();
            },
            success: function(data) {
                $('.modal_close').click();
                $('#overlayer').hide();
                $("#NoticeForm").trigger('reset');
                var result = jQuery.parseJSON(data);
                if (result.status == 'error') {
                    var msg = result.msg;
                    toastr.error(msg);
                } else {
                    var msg = result.msg;
                    toastr.success(msg);
                }
                loadnotice();
            }
        })
    }
}); --}}
