@extends('setting.layouts.main')
@section('setting_content')
@push('title')
<title>Banner Settings</title>
@endpush
@php
define('INNER_PAGE', 'banner');
define('PAGE_BREADCRUMB', 'Banner Setting');
@endphp

    <div class="row">
        <div class="col-sm-12">
            <div class="card card-table">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Banners</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="{{ route('admin.setting.banner_add') }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Add Banner"
                                 class="btn btn-primary"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($banners as $banner)
                            <div class="col-md-6 col-xl-4 col-sm-12 d-flex">
                                <div class="blog grid-blog flex-fill">
                                    <div class="blog-image">
                                        <a href="blog-details.html">
                                            <img class="img-fluid" src="{{ asset("uploads/images/banner/$banner->banner")}}" alt="Post Image">
                                        </a>
                                    </div>
                                    <div class="blog-content">
                                        <h3 class="blog-title"><a href="#">{{$banner->title}}</a></h3>
                                        <p>{{$banner->description}}</p>
                                    </div>
                                    <div class="row">
                                        <div class="edit-options">
                                            <div class="edit-delete-btn">
                                                <a href="#" data-banner_id="{{$banner->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Delete Banner"
                                                     class="text-danger" id="bannerDltBtn"><i
                                                        class="feather-trash-2 me-1"></i>Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-3">
                            {{$banners->links()}}
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

            $(document).on("click", "#bannerDltBtn", function(e) {
                e.preventDefault();
                var banner_id = $(this).data("banner_id");
                DeleteRecord(banner_id, "{{ route('banner.delete') }}", CallBack);

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
