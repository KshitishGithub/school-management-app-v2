@extends('layouts.master')
@section('content')
    @push('title')
        <title>Event List</title>
    @endpush
    @php
        define('PAGE', 'event_list');
    @endphp
    {{-- message --}}
    {{-- {!! Toastr::message() !!} --}}
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Event</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Event</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="page-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h3 class="page-title">Events</h3>
                                    </div>
                                    <div class="col-auto text-end float-end ms-auto download-grp">
                                        <a href="{{ route('event.add') }}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Add New Event"
                                         class="btn btn-primary"><i
                                                class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @foreach ($events as $event)
                                    <div class="col-md-6 col-xl-4 col-sm-12 d-flex">
                                        <div class="blog grid-blog flex-fill">
                                            <div class="blog-image">
                                                <a href="blog-details.html"><img class="img-fluid"
                                                        src="{{ url("uploads/images/event/$event->banner")}}"
                                                        alt="Post Image"></a>
                                                {{-- <div class="blog-views">
                                                <i class="feather-eye me-1"></i> 225
                                            </div> --}}
                                            </div>
                                            <div class="blog-content">
                                                <ul class="entry-meta meta-item">
                                                    <li>
                                                        <div class="post-author">
                                                            <a href="#">
                                                                <span>
                                                                    <span class="post-date"><i class="far fa-clock"></i>
                                                                        {{ \Carbon\Carbon::parse($event->created_at)->format('d-M-Y') }}</span>
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <h3 class="blog-title"><a href="#">{{$event->title}}</a></h3>
                                                <p>{{$event->description}}</p>
                                            </div>
                                            <div class="row">
                                                <div class="edit-options">
                                                    <div class="edit-delete-btn">
                                                        <a href="#" data-event_id="{{$event->id}}" data-bs-toggle="tooltip" data-bs-placement="top" title data-bs-original-title="Delete Event" class="text-danger" id="eventDltBtn"><i
                                                                class="feather-trash-2 me-1"></i>Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                {{-- <div class="modal fade contentmodal" id="deleteModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content doctor-profile">
                                            <div class="modal-header pb-0 border-bottom-0  justify-content-end">
                                                <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><i
                                                        class="feather-x-circle"></i></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="delete-wrap text-center">
                                                    <div class="del-icon"><i class="feather-x-circle"></i></div>
                                                    <h2>Sure you want to delete</h2>
                                                    <div class="submit-section">
                                                        <a href="blog.html" class="btn btn-success me-2">Yes</a>
                                                        <a href="#" class="btn btn-danger" data-bs-dismiss="modal">No</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="mt-3">
                                    {{$events->links()}}
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

            // Delete Event .........

            $(document).on("click", "#eventDltBtn", function(e) {
                e.preventDefault();
                var event_id = $(this).data("event_id");
                DeleteRecord(event_id, "{{ route('event.delete') }}", CallBack);

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
