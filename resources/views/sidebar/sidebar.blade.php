<div class="sidebar" id="sidebar">
    <!-- Loader -->
    <div id="overlayer" class="container-fluid" style="display: none">
        <div class="row">
            <div id="loading">
                <img src="{{ url('assets/img/loader/loader.gif') }}">
                <span>Loading....</span>
            </div>
        </div>
    </div>


    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="{{ PAGE == 'admin_dashboard' ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="feather-grid"></i><span>Dashboard</span></a>
                </li>

                {{-- <li class="submenu {{ PAGE == 'admin_dashboard' ? 'active' : '' }}">
                    <a href="#"><i class="feather-grid"></i>
                        <span> Dashboard</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.dashboard') }}"
                                class="{{ PAGE == 'admin_dashboard' ? 'active' : '' }}">Admin Dashboard</a></li>
                        <li><a href="{{ route('admin.dashboard') }}" class="">Teacher Dashboard</a></li>
                    </ul>
                </li> --}}
                @if (Auth::user()->role == '3' || Auth::user()->role == '4' || Auth::user()->role == '2')
                    <li class="submenu {{ PAGE == 'registration' || PAGE == 'registered' ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-registered" aria-hidden="true"></i>
                            <span> Registration</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li>
                                <a class="{{ PAGE == 'registration' ? 'active' : '' }}"
                                    href="{{ route('registration') }}"><i class="fas fa-user-plus"></i>
                                    <span>New Admission</span></a>
                            </li>
                            <li>
                                <a class="{{ PAGE == 'registered' ? 'active' : '' }}"
                                    href="{{ route('registered') }}"><i class="fas fa-user"></i>
                                    <span>Registered</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ PAGE == 'admin_setting' ? 'active' : '' }}">
                        <a
                            href="{{ Auth::user()->role == 4 ? route('admin.setting') : route('admin.setting.banner') }}">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li
                        class="submenu  {{ PAGE == 'teacher_list' || PAGE == 'teacher_add' || PAGE == 'teacher_profile' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-chalkboard-teacher"></i>
                            <span> Teachers</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('teacher.list') }}"
                                    class="{{ PAGE == 'teacher_list' ? 'active' : '' }}">Teacher List</a></li>
                            <li><a href="{{ route('teacher.create') }}"
                                    class="{{ PAGE == 'teacher_add' ? 'active' : '' }}">Teacher Add</a></li>
                            {{-- <li><a href="{{ route('teacher.profile') }}" class="{{ PAGE == 'teacher_profile' ? 'active' : '' }}">Teacher View</a></li> --}}
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->role == '2' || Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li
                        class="submenu {{ PAGE == 'subject_list' || PAGE == 'subject_add' || PAGE == 'subject_edit' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-book-reader"></i>
                            <span> Subjects</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a class="{{ PAGE == 'subject_list' ? 'active' : '' }}"
                                    href="{{ route('subject.list') }}">Subject List</a></li>
                            <li><a class="{{ PAGE == 'subject_add' ? 'active' : '' }}"
                                    href="{{ route('subject.add') }}">Subject Add</a></li>
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->role == '2' || Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li
                        class="submenu {{ PAGE == 'fees' || PAGE == 'paid_fees' || PAGE == 'collection_status' || PAGE == 'due_fees' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-comment-dollar"></i>
                            <span> Fees</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a class="{{ PAGE == 'fees' ? 'active' : '' }}" href="{{ route('fees.list') }}">Fees
                                    Collection</a></li>
                            <li><a class="{{ PAGE == 'collection_status' ? 'active' : '' }}"
                                    href="{{ route('fees.collection.status') }}">Details of fees</a></li>
                            {{-- <li><a class="{{ PAGE == 'paid_fees' ? 'active' : '' }}" href="{{ route('fees.paid') }}">Paid
                                Students</a></li> --}}
                            <li><a class="{{ PAGE == 'due_fees' ? 'active' : '' }}" href="{{ route('fees.due') }}">Students fees report</a></li>
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->role >= '2')
                    {{-- <li
                        class="submenu {{ PAGE == 'department_list' || PAGE == 'department_add' || PAGE == 'department_edit' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-building"></i>
                            <span> Departments</span>
                            <span class="menu-arrow"></span>
                        </a>
                         <ul>
                            <li><a href="{{ route('department.list') }}"
                                    class="{{ PAGE == 'department_list' ? 'active' : '' }}">Department List</a></li>
                            <li><a href="{{ route('department.add') }}"
                                    class="{{ PAGE == 'department_add' ? 'active' : '' }}">Department Add</a></li>
                            <li><a href="{{ route('department.edit') }}"
                                    class="{{ PAGE == 'department_edit' ? 'active' : '' }}">Department Edit</a></li>
                        </ul>
                    </li> --}}
                @endif
                @if (Auth::user()->role == '2' || Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li class="{{ PAGE == 'session' ? 'active' : '' }}">
                        <a href="{{ route('session') }}"><i
                                class="fa-solid fa-calendar-days"></i><span>Session</span></a>
                    </li>
                @endif
                @if (Auth::user()->role == '2' || Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li class="submenu {{ PAGE == 'class_list' || PAGE == 'class_add' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-edit"></i>
                            <span> Classes</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('class.list') }}"
                                    class="{{ PAGE == 'class_list' ? 'active' : '' }}">Class List</a></li>
                            <li><a href="{{ route('class.add') }}"
                                    class="{{ PAGE == 'class_add' ? 'active' : '' }}">Class
                                    Add</a></li>
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->role == '2' || Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li class="submenu {{ PAGE == 'section_list' || PAGE == 'section_add' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-th-list"></i>
                            <span> Section</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('section.list') }}"
                                    class="{{ PAGE == 'section_list' ? 'active' : '' }}">Section List</a></li>
                            <li><a href="{{ route('section.add') }}"
                                    class="{{ PAGE == 'section_add' ? 'active' : '' }}">Section Add</a></li>
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->role == '1' || Auth::user()->role == '2' || Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li class="submenu {{ PAGE == 'students' || PAGE == 'students_profile' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-graduation-cap"></i>
                            <span> Students</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('students.list') }}"
                                    class="{{ PAGE == 'students' ? 'active' : '' }}">Student List</a></li>
                            {{-- <li><a href="{{ route('students.profile') }}"  class="{{ PAGE == 'students_profile' ? 'active' : '' }}">Student View</a></li> --}}
                        </ul>
                    </li>
                    <li
                        class="submenu {{ PAGE == 'hostel' || PAGE == 'out_hostel_qr_attendance' || PAGE == 'present_hosteller' || PAGE == 'hostel_qr_attendance' || PAGE == 'hostel_view_attendance' ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-home" aria-hidden="true"></i>
                            <span> Hostel</span>
                            <span class="menu-arrow"></span>
                        </a>

                        {{-- <ul>
                            <li><a href="{{ route('hostel.list') }}"
                                    class="{{ PAGE == 'hostel' ? 'active' : '' }}">Hostellers</a></li>

                            <li><a href="{{ route('hostel.attendance.view') }}"
                                    class="{{ PAGE == 'hostel_view_attendance' ? 'active' : '' }}">Attendance </a>
                            </li>
                            <li><a href="{{ route('hostel.evening_attendance') }}"
                                    class="{{ PAGE == 'evening_attendance' ? 'active' : '' }}">Evening Attendance </a>
                            </li>
                        </ul> --}}
                        <ul>
                            <li><a href="{{ route('hostel.list') }}"
                                    class="{{ PAGE == 'hostel' ? 'active' : '' }}">Hostellers</a></li>
                            <li><a href="{{ route('hostel.present') }}"
                                    class="{{ PAGE == 'present_hosteller' ? 'active' : '' }}">Present Hosteller </a>
                            </li>
                            <li><a href="{{ route('hostel.inOutRecord') }}"
                                    class="{{ PAGE == 'in_out_record' ? 'active' : '' }}">In Out Record </a>
                            </li>
                            <li
                                class="submenu {{ PAGE == 'hostel_manual' || PAGE == 'hostel_qr_attendance' || PAGE == 'hostel_fingerprint_attendance' ? 'active' : '' }}">
                                <a href="javascript:void(0);"><i class="fa-solid fa-left-long"></i> <span>IN</span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    {{-- <li><a href="{{ route('hostel.attendance.fill') }}"
                                            class="{{ PAGE == 'hostel_manual' ? 'active' : '' }}">Manual
                                            Attendance
                                        </a>
                                    </li>
                                    <li><a href="{{ route('hostel.fingerprint.fill') }}"
                                            class="{{ PAGE == 'hostel_fingerprint_attendance' ? 'active' : '' }}">Fingerprint
                                            Attendance </a>
                                    </li> --}}
                                    <li><a href="{{ route('hostel.qr.in') }}"
                                            class="{{ PAGE == 'hostel_qr_attendance' ? 'active' : '' }}">QR Attendance
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li
                                class="submenu {{ PAGE == 'hostel_out_manual_attendance' || PAGE == 'hostel_out_fingerprint_attendance' || PAGE == 'out_hostel_qr_attendance' ? 'active' : '' }}">
                                <a href="javascript:void(0);"> <i class="fa-solid fa-right-long"></i><span>Out</span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    {{-- <li><a href="{{ route('hostel.attendance.fill') }}"
                                            class="{{ PAGE == 'hostel_out_manual_attendance' ? 'active' : '' }}">Manual
                                            Attendance
                                        </a>
                                    </li>
                                    <li><a href="{{ route('hostel.fingerprint.fill') }}"
                                            class="{{ PAGE == 'hostel_out_fingerprint_attendance' ? 'active' : '' }}">Fingerprint
                                            Attendance </a>
                                    </li> --}}
                                    <li><a href="{{ route('hostel.qr.out') }}"
                                            class="{{ PAGE == 'out_hostel_qr_attendance' ? 'active' : '' }}">QR
                                            Attendance
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li
                                class="submenu {{ PAGE == 'qr_evening_attendance' || PAGE == 'evening_attendance' || PAGE == 'evening_attendance_left' ? 'active' : '' }}">
                                <a href="javascript:void(0);"> <i class="fa-solid fa-sun"></i><span>Evening</span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="{{ route('hostel.evening_attendance') }}"
                                            class="{{ PAGE == 'qr_evening_attendance' ? 'active' : '' }}">QR
                                            Attendance </a>
                                    </li>
                                    <li><a href="{{ route('hostel.evening_attendance_done') }}"
                                            class="{{ PAGE == 'evening_attendance' ? 'active' : '' }}">Attendance
                                            done</a>
                                    </li>
                                    <li><a href="{{ route('hostel.evening_attendance_left') }}"
                                            class="{{ PAGE == 'evening_attendance_left' ? 'active' : '' }}">Attendance
                                            left</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="submenu {{ PAGE == 'finger' || PAGE == 'add_finger' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-fingerprint"></i><span> Finger Print</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('finger.list') }}"
                                    class="{{ PAGE == 'finger' ? 'active' : '' }}">Add
                                    Finger Print</a></li>
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->role == '1' || Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li
                        class="submenu {{ PAGE == 'fill_attendance' || PAGE == 'view_attendance' || PAGE == 'qr_attendance' || PAGE == 'fingerprint_attendance' ? 'active' : '' }}">
                        <a href="#"><i class="fa-solid fa-pen-nib"></i>
                            <span> Attendance </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li
                                class="submenu {{ PAGE == 'fill_attendance' || PAGE == 'qr_attendance' || PAGE == 'fingerprint_attendance' ? 'active' : '' }}">
                                <a href="javascript:void(0);"><i class="fa-solid fa-left-long"></i> <span>IN</span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="{{ route('attendance.fill') }}"
                                            class="{{ PAGE == 'fill_attendance' ? 'active' : '' }}"> Manual Attendance
                                        </a>
                                    </li>
                                    <li><a href="{{ route('fingerprint.fill') }}"
                                            class="{{ PAGE == 'fingerprint_attendance' ? 'active' : '' }}">Fingerprint
                                            Attendance </a>
                                    </li>
                                    <li><a href="{{ route('qr.fill') }}"
                                            class="{{ PAGE == 'qr_attendance' ? 'active' : '' }}">QR Attendance </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="submenu {{ PAGE == 'out_qr_attendance' ? 'active' : '' }}">
                                <a href="javascript:void(0);"> <i class="fa-solid fa-right-long"></i><span>Out</span>
                                    <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a href="{{ route('out.qr.fill') }}"
                                            class="{{ PAGE == 'out_qr_attendance' ? 'active' : '' }}">Out QR
                                            Attendance
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="{{ route('attendance.view') }}"
                                    class="{{ PAGE == 'view_attendance' ? 'active' : '' }}">Attendance </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->role == '1' || Auth::user()->role == '2' || Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li class="{{ PAGE == 'id_card' ? 'active' : '' }}">
                        <a href="{{ route('student.idcard') }}"><i class="fa-solid fa-id-card"></i> <span>Student
                                ID
                                Card</span></a>
                    </li>
                @endif
                @if (Auth::user()->role == '1' || Auth::user()->role == '2' || Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li class="submenu {{ PAGE == 'exam_list' || PAGE == 'exam_add' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-clipboard-list"></i>
                            <span> Exam List</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('exam.list') }}"
                                    class="{{ PAGE == 'exam_list' ? 'active' : '' }}">Exam List</a></li>
                            <li><a href="{{ route('exam.add') }}"
                                    class="{{ PAGE == 'exam_add' ? 'active' : '' }}">Exam
                                    Add</a></li>
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->role == '1' || Auth::user()->role == '2' || Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li class="submenu {{ PAGE == 'add_marks' || PAGE == 'mark_sheet' || PAGE == 'published' || PAGE == 'unitTestResult' || PAGE == 'final_mark_sheet' || PAGE == 'result' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-clipboard"></i>
                            <span>Manage Exam </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @if (Auth::user()->role == '1' || Auth::user()->role == '3' || Auth::user()->role == '4')
                                <li><a href="{{ route('exam.add_marks') }}"
                                        class="{{ PAGE == 'add_marks' ? 'active' : '' }}">Add Marks</a></li>
                            @endif
                            <li><a href="{{ route('exam.result') }}"
                                    class="{{ PAGE == 'result' ? 'active' : '' }}">Student Result</a></li>
                            <li><a href="{{ route('exam.unitTest.result') }}"
                                class="{{ PAGE == 'unitTestResult' ? 'active' : '' }}">Unit Test Result</a></li>
                            <li><a href="{{ route('exam.published') }}"
                                    class="{{ PAGE == 'published' ? 'active' : '' }}">Published</a></li>
                            <li><a href="{{ route('exam.mark_sheet') }}"
                                    class="{{ PAGE == 'mark_sheet' ? 'active' : '' }}">Mark Sheet</a></li>
                            {{--  <li><a href="{{ route('exam.final_mark_sheet') }}"
                                class="{{ PAGE == 'final_mark_sheet' ? 'active' : '' }}">Final Mark Sheet</a></li>  --}}
                        </ul>
                    </li>
                    {{--  Pass out the students  --}}
                    <li class="submenu {{ PAGE == 'passout' ? 'active' : '' }}">
                        <a href="#"><i class="fa-solid fa-arrow-right"></i>
                            <span>Pass Out </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            @if (Auth::user()->role == '1' || Auth::user()->role == '3' || Auth::user()->role == '4')
                                <li><a href="{{ route('passout') }}"
                                        class="{{ PAGE == 'passout' ? 'active' : '' }}">Pass Out</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->role == '2' || Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li class="submenu {{ PAGE == 'event_list' || PAGE == 'event_add' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-calendar-day"></i>
                            <span> Event</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('event.list') }}"
                                    class="{{ PAGE == 'event_list' ? 'active' : '' }}">Eenet List</a></li>
                            <li><a href="{{ route('event.add') }}"
                                    class="{{ PAGE == 'event_add' ? 'active' : '' }}">Event Add</a></li>
                        </ul>
                    </li>
                    <li
                        class="submenu {{ PAGE == 'pending_leave' || PAGE == 'approve_leave' || PAGE == 'reject_leave' ? 'active' : '' }}">
                        <a href="#"><i class="bi bi-arrow-return-left"></i>
                            <span> Student Leave</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('leave.pending') }}"
                                    class="{{ PAGE == 'pending_leave' ? 'active' : '' }}">Pending Leave</a></li>
                            <li><a href="{{ route('leave.approved') }}"
                                    class="{{ PAGE == 'approve_leave' ? 'active' : '' }}">Approved Leave</a></li>
                            <li><a href="{{ route('leave.reject') }}"
                                    class="{{ PAGE == 'reject_leave' ? 'active' : '' }}">Rejected Leave</a></li>
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->role == '2' || Auth::user()->role == '3' || Auth::user()->role == '4')
                    <li class="submenu {{ PAGE == 'holiday_list' || PAGE == 'holiday_add' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-holly-berry"></i>
                            <span> Holiday</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('holiday.list') }}"
                                    class="{{ PAGE == 'holiday_list' ? 'active' : '' }}">Holiday List</a></li>
                            <li><a href="{{ route('holiday.add') }}"
                                    class="{{ PAGE == 'holiday_add' ? 'active' : '' }}">Holiday Add</a></li>
                        </ul>
                    </li>
                    <li
                        class="submenu {{ PAGE == 'bus_list' || PAGE == 'bus_add' || PAGE == 'route' || PAGE == 'bus_stop' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-bus"></i>
                            <span> Manage Bus</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('bus.list') }}"
                                    class="{{ PAGE == 'bus_list' ? 'active' : '' }}">Bus
                                    List</a></li>
                            <li><a href="{{ route('bus.add') }}"
                                    class="{{ PAGE == 'bus_add' ? 'active' : '' }}">Bus
                                    Add</a></li>
                            <li><a href="{{ route('route.index') }}"
                                    class="{{ PAGE == 'route' ? 'active' : '' }}">Route</a></li>
                            <li><a href="{{ route('bus_stop.index') }}"
                                    class="{{ PAGE == 'bus_stop' ? 'active' : '' }}">Bus Stops</a></li>
                        </ul>
                    </li>
                    <li class="submenu {{ PAGE == 'form_print' || PAGE == 'form_download' ? 'active' : '' }}">
                        <a href="#"><i class="fa-regular fa-file"></i>
                            <span>Blank Form</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a target="_blank" href="{{ route('form.print') }}"
                                    class="{{ PAGE == 'form_print' ? 'active' : '' }}">Print Form</a></li>
                            <li><a target="_blank" href="{{ route('form.download') }}"
                                    class="{{ PAGE == 'form_download' ? 'active' : '' }}">Download Form</a></li>
                        </ul>
                    </li>
                    <li class="submenu {{ PAGE == 'fees_type' ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-usd" aria-hidden="true"></i>
                            <span>Fees Type</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a target="_blank" href="{{ route('fees_type.list') }}"
                                    class="{{ PAGE == 'fees_type' ? 'active' : '' }}">Fees Type List</a></li>
                        </ul>
                    </li>
                    <li
                        class="submenu {{ PAGE == 'library' || PAGE == 'sell' ? 'active' : '' }}">
                        <a href='#'><i class="fa-solid fa-book"></i>
                            <span>Library</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a class="{{ PAGE == 'library' ? 'active' : '' }}" href="{{ route('library.index') }}"><i class="fa-solid fa-book"></i>
                                <span> Library Stock</span><span class="menu-arrow"></span>
                                </a>
                            </li>
                            <li><a class="{{ PAGE == 'sell' ? 'active' : '' }}" href="{{ route('library.sell') }}"><i class="fa-solid fa-book-open"></i>
                                <span> Sell Stock</span><span class="menu-arrow"></span>
                                </a>
                            </li>
                            <li><a class="{{ PAGE == 'details' ? 'active' : '' }}" href="{{ route('salesDetails') }}"><i class="fa-solid fa-book-open"></i>
                                <span> Sell Details</span><span class="menu-arrow"></span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (Auth::user()->role == '4')
                    <li
                        class="submenu {{ PAGE == 'user_list' || PAGE == 'user_edit' || PAGE == 'user_add' ? 'active' : '' }}">
                        <a href="#"><i class="fas fa-users"></i>
                            <span> Users</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('user.list') }}"
                                    class="{{ PAGE == 'user_list' ? 'active' : '' }}">User List</a></li>
                            <li><a href="{{ route('user.add') }}"
                                    class="{{ PAGE == 'user_add' ? 'active' : '' }}">User
                                    Add</a></li>
                        </ul>
                    </li>
                    <li
                        class="submenu {{ PAGE == 'committee_list' || PAGE == 'committee_edit' || PAGE == 'committee_add' ? 'active' : '' }}">
                        <a href="#"><i class="fa fa-user-secret" aria-hidden="true"></i>
                            <span> Committees</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('committee.index') }}"
                                    class="{{ PAGE == 'committee_list' ? 'active' : '' }}">Committee List</a></li>
                            <li><a href="{{ route('committee.create') }}"
                                    class="{{ PAGE == 'committee_add' ? 'active' : '' }}">Add Committee</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
