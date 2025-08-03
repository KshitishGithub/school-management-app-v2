<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('title')
    @if (session()->has('admin_settings') && !empty(session('admin_settings')[0]['favicon']))
        <link rel="shortcut icon" href="{{ asset('uploads/images/setting/' . session('admin_settings')[0]['favicon']) }}">
    @endif
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/icons/flags/flags.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/css/bootstrap-datetimepicker.min.cs') }}s">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/simple-calendar/simple-calendar.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/css/ckeditor.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/css/custom_style.css') }}">
    {{-- message toastr --}}
    <link rel="stylesheet" href="{{ URL::to('assets/css/toastr.min.css') }}">
    {{-- Fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    {{-- Bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="{{ URL::to('assets/js/toastr_jquery.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/toastr.min.js') }}"></script>
    <!--sweet alert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
{{-- successfully sound --}}
<audio id="success-sound" src="{{ asset('assets/sound/success.mp3') }}" preload="auto"></audio>
<audio id="error-sound" src="{{ asset('assets/sound/error.mp3') }}" preload="auto"></audio>
<audio id="bye" src="{{ asset('assets/sound/bye.mp3') }}" preload="auto"></audio>
<audio id="welcome" src="{{ asset('assets/sound/welcome.mp3') }}" preload="auto"></audio>
<audio id="radhe" src="{{ asset('assets/sound/radhe.mp3') }}" preload="auto"></audio>

    <div class="main-wrapper">

        <div class="header">
            <div class="header-left">
                @if (session()->has('admin_settings') && !empty(session('admin_settings')[0]['favicon']))
                    <a href="{{ route('admin.dashboard') }}" class="logo">
                        <img src="{{ asset('uploads/images/setting/' . session('admin_settings')[0]['logo']) }}"
                            alt="Logo">
                    </a>
                    {{-- <a href="{{ route('admin.dashboard') }}" class="logo logo-small">
                        <img src='{{ url('storage/images/setting/' . session('admin_settings')[0]['favicon']) }} '
                            alt="Logo" width="30" height="30">
                    </a> --}}
                @endif
            </div>
            <div class="menu-toggle">
                <a href="javascript:void(0);" id="toggle_btn">
                    <i class="fas fa-bars"></i>
                </a>
            </div>

            {{-- <div class="top-nav-search">
                <form>
                    <input type="text" class="form-control" placeholder="Search here">
                    <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div> --}}
            <a class="mobile_btn" id="mobile_btn">
                <i class="fas fa-bars"></i>
            </a>
            <ul class="nav user-menu">
                <style>
                    #clock {
                        font-family: 'Orbitron', sans-serif;
                        font-size: 18px;
                        color: #333;
                        text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.1);
                        padding: 8px 20px;
                        border-radius: 8px;
                        /* background-color: #fff; */
                        /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
                    }
                </style>
                <li class="nav-item top-nav-search">
                    <div id="clock"></div>
                </li>
                <li class="nav-item zoom-screen me-2">
                    <a href="#" class="nav-link header-nav-list win-maximize">
                        <img src="{{ url('assets/img/icons/header-icon-04.svg') }}" alt="">
                    </a>
                </li>

                <li class="nav-item dropdown has-arrow new-user-menus">
                    <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle"
                                src="{{ asset('uploads/images/user/' . auth()->user()->profile_image) }}"
                                width="31"alt="{{ auth()->user()->name }}">
                            <div class="user-text">
                                <h6>{{ auth()->user()->name }}</h6>
                                <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                            </div>
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="user-header">
                            <div class="avatar avatar-sm">
                                <img src="{{ asset('uploads/images/user/' . auth()->user()->profile_image) }}"
                                    alt="{{ auth()->user()->name }}" class="avatar-img rounded-circle">
                            </div>
                            <div class="user-text">
                                <h6>{{ auth()->user()->name }}</h6>
                                <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        {{-- <a class="dropdown-item" href="">My Profile</a> --}}
                        <a class="dropdown-item" href="{{ route('admin.change_password') }}">Change password</a>
                        <a class="dropdown-item" id="logout" onclick="logout()" href="javascript:;">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
        {{-- side bar --}}
        @include('sidebar.sidebar')
        {{-- content page --}}
        @yield('content')
        <footer>
            <p>Copyright© {{ date('Y') }} School Siksha PVT. LTD.</p>
        </footer>

    </div>
    <script type="text/javascript" src="{{ URL::to('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/feather.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/plugins/apexchart/chart-data.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/plugins/simple-calendar/jquery.simple-calendar.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/calander.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/circle-progress.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/ckeditor.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/plugins/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/script.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/input-mask.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/formValidation.js') }}"></script>
    {{-- instascan web cam --}}
    <script src="{{ URL::to('assets/js/instascan.min.js') }}"></script>
    {{-- Sweet alert --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        // Input Mask
        $(function() {
            $('[data-mask]').inputmask();
        });

        // CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    {{-- Toastr Message --}}
    @if (Session::has('toastr'))
        <script script>
            var toastrData = @json(Session::get('toastr'));
            toastr.options = {
                positionClass: 'toast-top-right',
                "closeButton": true,
                "progressBar": true

            };
            toastr[toastrData.type](toastrData.message);
        </script>
    @endif

    {{-- Image Preview --}}
    <script>
        $('#imageInput').on('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            } else {
                $('#imagePreview').attr('src', '');
            }
        });

        // Clock function
        function updateClock() {
            var now = new Date();
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var dayOfWeek = days[now.getDay()]; // Get the day of the week

            var date = now.toLocaleDateString('en-IN', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });

            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            var ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12; // Handle midnight (0 hours)

            var time = hours.toString().padStart(2, '0') + ':' +
                minutes.toString().padStart(2, '0') + ':' +
                seconds.toString().padStart(2, '0') + ' ' + ampm;

            var clockDisplay = dayOfWeek + ', ' + date + ' ' + time; // Include the day of the week
            document.getElementById('clock').innerText = clockDisplay;
        }

        setInterval(updateClock, 1000);
        updateClock();

        $(document).ready(function() {
            // logout finction
            $("#logout").click(function(e) {
                e.preventDefault();
                logout("{{ route('admin.logout') }}", '/');
            });
        });

        // Send an AJAX request to update isLogin to 0 before the browser/tab is closed
        // window.addEventListener('beforeunload', function(event) {
        //     fetch('{{ route('admin.logout') }}', {
        //         method: 'GET'
        //     });
        // });
    </script>
    @yield('customJS')
</body>

</html>

<?php
    // FirebasePushNotification(['1'],'Kakuli Barman','Body',"https://www.gstatic.com/mobilesdk/160503_mobilesdk/logo/2x/firebase_28dp.png");

    // OneSignalPushNotification('ok test gps','Success',"https://test.schoolsiksha.com/storage/images/setting/1712646426_logo.png","https://test.schoolsiksha.com/storage/images/setting/1712646426_logo.png");
?>
