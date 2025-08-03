@extends('layouts.master')
@section('content')
    @push('title')
        <title>Add Finger Print</title>
    @endpush
    @php
        define('PAGE', 'add_finger');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-sub-header">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                <li class="breadcrumb-item active">Add Finger Print</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="page-title">Add Finger</h3>
                            </div>
                            <div class="col-auto text-end float-end ms-auto download-grp">
                                <a href="#" onclick="window.history.back();" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Back" class="btn btn-primary">
                                    <i class="fa fa-backward" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row text-center mt-3">
                                <div class="col-3"><button onclick="capture({{ $students->id }}, 'left_little');" id="left_little"
                                        class="btn btn-sm {{ valueExistsInArray('left_little', $fingerArray) ? 'btn-success' : 'btn-dark' }}">Little</button></div>
                                <div class="col-2"><button onclick="capture({{ $students->id }}, 'left_ring');" id="left_ring"
                                        class="btn btn-sm {{ valueExistsInArray('left_ring', $fingerArray) ? 'btn-success' : 'btn-dark' }}">Ring</button></div>
                                <div class="col-2"><button onclick="capture({{ $students->id }}, 'left_middle');" id="left_middle"
                                        class="btn btn-sm {{ valueExistsInArray('left_middle', $fingerArray) ? 'btn-success' : 'btn-dark' }}">Middle</button></div>
                                <div class="col-2"><button onclick="capture({{ $students->id }}, 'left_index');" id="left_index"
                                        class="btn btn-sm {{ valueExistsInArray('left_index', $fingerArray) ? 'btn-success' : 'btn-dark' }}">Index</button></div>
                                <div class="col-3"><button onclick="capture({{ $students->id }}, 'left_thumb');" id="left_thumb"
                                        class="btn btn-sm {{ valueExistsInArray('left_thumb', $fingerArray) ? 'btn-success' : 'btn-dark' }}">Thumb</button></div>
                                <div class="col-12 text-center mt-3">
                                    <img class="img-fluid" src="{{ url('assets/img/left_hand.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 text-center mt-5 g-3">
                            <img height="170" width="110" id="scanning" class="img-fluid img-thumbnail"
                                src="{{ url('assets/img/finger.png') }}" alt="">
                        </div>
                        <div class="col-md-5 mt-3 g-3">
                            <div class="row text-center">
                                <div class="col-3"><button onclick="capture({{ $students->id }}, 'right_thumb');" id="right_thumb"
                                        class="btn btn-sm {{ valueExistsInArray('right_thumb', $fingerArray) ? 'btn-success' : 'btn-dark' }}">Thumb</button></div>
                                <div class="col-2"><button onclick="capture({{ $students->id }}, 'right_index');" id="right_index"
                                        class="btn btn-sm {{ valueExistsInArray('right_index', $fingerArray) ? 'btn-success' : 'btn-dark' }}">Index</button></div>
                                <div class="col-2"><button onclick="capture({{ $students->id }}, 'right_middle');" id="right_middle"
                                        class="btn btn-sm {{ valueExistsInArray('right_middle', $fingerArray) ? 'btn-success' : 'btn-dark' }}">Middle</button></div>
                                <div class="col-2"><button onclick="capture({{ $students->id }}, 'right_ring');" id="right_ring"
                                        class="btn btn-sm {{ valueExistsInArray('right_ring', $fingerArray) ? 'btn-success' : 'btn-dark' }}">Ring</button></div>
                                <div class="col-3"><button onclick="capture({{ $students->id }}, 'right_little');" id="right_little"
                                        class="btn btn-sm {{ valueExistsInArray('right_little', $fingerArray) ? 'btn-success' : 'btn-dark' }}">Little</button></div>
                                <div class="col-12 text-center mt-3">
                                    <img class="img-fluid" src="{{ url('assets/img/right_hand.png') }}" alt="">
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
    {{-- Finger Print --}}
    <script src="{{ url('assets/finger_print/js/mfs100.js') }}"></script>
    <script src="{{ url('assets/finger_print/js/jquery-1.8.2.js') }}"></script>

    <script>
        var quality = 60;
        var timeout = 10;
        var nooffinger = '1';

        // Capture the fingerprint
        function capture(id, fingerImage) {
            $('.btn').attr('disabled', true);
            $('#scanning').attr("src", "{{ url('assets/img/scanning.gif') }}");

            setTimeout(function() {

                var res = CaptureFinger(quality, timeout);

                if (res.httpStaus) {
                    if (res.data.ErrorCode == '0') {
                        $('.btn').attr('disabled', false);
                        $('#'+fingerImage).addClass('btn-success');
                        $('#scanning').attr("src", "data:image/bmp;base64," + res.data.BitmapData);
                        sendToServer(id, fingerImage, res.data.IsoTemplate);
                        // console.log(res.data.IsoTemplate);
                    } else {
                        $('.btn').attr('disabled', false);
                        $('#scanning').attr("src", "{{ url('assets/img/finger.png') }}");
                        alert(res.data.ErrorDescription);
                    }
                } else {
                    alert(res.err);
                }
            }, 1000)
        }

        // Send to server
        function sendToServer(id, fingerImage, IsoTemplate) {
            $.ajax({
                url: "{{ route('fingers.store') }}",
                type: "POST",
                data: {
                    id: id,
                    finger: fingerImage,
                    isotemplate: IsoTemplate
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(response) {
                    msg(response);
                },
                error: function(error) {
                    console.error("Error sending data to server:", error);
                }
            });
        }
    </script>
@endsection
