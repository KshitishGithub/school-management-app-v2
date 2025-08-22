<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ClassManagementController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\downloadPdfController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\FeesController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\IdCardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BusStopController;
use App\Http\Controllers\FingerPrintController;
use App\Http\Controllers\HostelAttendanceController;
use App\Http\Controllers\HostelController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\Mail\AddUserController;
use App\Http\Controllers\Mail\MailController;
use App\Http\Controllers\ManageBusController;
use App\Http\Controllers\ManageExamController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SendSmsController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SetPriceController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;



Route::get('/send_sms', [SendSmsController::class, 'sendSMS']);
Route::get('test-mail', [MailController::class, 'test']);
Route::group(['prefix' => 'user', 'middleware' => 'LoggedInCheck'], function () {
    // Login ...............
    Route::get('/login', [AuthController::class, 'index'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
    // Forgot Password ...............
    Route::get('/reset_password', [ForgotPasswordController::class, 'index'])->name('admin.forgotpassword');
    Route::post('/reset_password', [MailController::class, 'resetPassword'])->name('admin.send_link');
    Route::get('/update_password', [ForgotPasswordController::class, 'updatePassword']);
    Route::post('/update_password', [ForgotPasswordController::class, 'updateNewPassword'])->name('admin.update.password');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::get('/', function () {
    return redirect('/dashboard');
});


Route::group(['prefix' => 'dashboard', 'middleware' => 'auth'], function () {

    // Dashboard ............
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/change_password', [AuthController::class, 'changePassword'])->name('admin.change_password');
    Route::post('/change_password', [AuthController::class, 'change_password'])->name('admin.password_change');


    Route::middleware('TeacherAndAdmin')->group(function () {
        // Students ............
        Route::get('/students', [StudentController::class, 'index'])->name('students.list');
        Route::get('/students/profile/{id}', [StudentController::class, 'profile'])->name('students.profile');

        // Hosteller Students ............
        Route::get('/hosteller', [HostelController::class, 'index'])->name('hostel.list');
        Route::get('/present-hosteller', [HostelController::class, 'present_hosteller'])->name('hostel.present');

        // Fingerprint route
        Route::get('/finger_print', [FingerPrintController::class, 'index'])->name('finger.list');
        Route::get('/add_finger_print/{id}', [FingerPrintController::class, 'add_finger'])->name('finger.add');
        Route::post('/fingers', [FingerPrintController::class, 'store'])->name('fingers.store');
        Route::get('/fingers', [FingerPrintController::class, 'show'])->name('fingers.show');

        // Attendance...........
        Route::get('/attendance/view', [AttendanceController::class, 'index'])->name('attendance.view');
        // get the month according to the year
        Route::get('/getAttendanceYear', [AttendanceController::class, 'getAttendanceYear'])->name('getAttendanceYear');
        Route::get('/attendance/fill', [AttendanceController::class, 'fill'])->name('attendance.fill');
        Route::get('/attendance/fingerprint-attendance', [AttendanceController::class, 'fingerprint'])->name('fingerprint.fill');
        Route::get('/attendance/qr-attendance', [AttendanceController::class, 'qr'])->name('qr.fill');
        Route::get('/attendance/out-qr-attendance', [AttendanceController::class, 'out_qr'])->name('out.qr.fill');
        Route::post('/attendance/modal', [AttendanceController::class, 'attendanceModal'])->name('fingerprint.modal');
        Route::post('/attendance/fill', [AttendanceController::class, 'fill_attendnce'])->name('attendance.fill_attendnce');
        Route::post('/attendance/out', [AttendanceController::class, 'getOutAttendance'])->name('attendance.out_attendnce');
        Route::get('/attendance/automaticAbsence', [AttendanceController::class, 'automaticAbsence'])->name('attendance.automaticAbsence');


        // Hostel Attendance
        Route::get('hostel/attendance/view', [HostelAttendanceController::class, 'index'])->name('hostel.attendance.view');
        // get the month according to the year
        Route::get('hostel/getAttendanceYear', [HostelAttendanceController::class, 'getAttendanceYear'])->name('hostel.getAttendanceYear');
        Route::get('hostel/attendance/fill', [HostelAttendanceController::class, 'fill'])->name('hostel.attendance.fill');
        Route::get('hostel/attendance/fingerprint-attendance', [HostelAttendanceController::class, 'fingerprint'])->name('hostel.fingerprint.fill');
        Route::get('hostel/attendance/in-qr-attendance', [HostelAttendanceController::class, 'in_qr_attendance'])->name('hostel.qr.in');
        Route::get('hostel/attendance/out-qr-attendance', [HostelAttendanceController::class, 'out_qr_attendance'])->name('hostel.qr.out');
        Route::post('hostel/attendance/modal', [HostelAttendanceController::class, 'attendanceModal'])->name('hostel.fingerprint.modal');
        Route::post('hostel/attendance/in', [HostelAttendanceController::class, 'in_attendnce'])->name('hostel.attendance.in_attendnce');
        Route::post('hostel/attendance/out', [HostelAttendanceController::class, 'out_attendnce'])->name('hostel.attendance.out_attendnce');
        Route::get('hostel/attendance/evening', [HostelAttendanceController::class, 'evening'])->name('hostel.evening_attendance');
        Route::post('hostel/attendance/evening', [HostelAttendanceController::class, 'fill_evening_attendance'])->name('hostel.fill_evening_attendance');
        Route::get('hostel/attendance/evening-attendance-done', [HostelAttendanceController::class, 'eveningAttendanceDone'])->name('hostel.evening_attendance_done');
        Route::get('hostel/attendance/evening-attendance-left', [HostelAttendanceController::class, 'eveningAttendanceLeft'])->name('hostel.evening_attendance_left');
        Route::get('hostel/attendance/in-out-record', [HostelAttendanceController::class, 'inOutRecord'])->name('hostel.inOutRecord');
    });

    // Get Section after selecting class......
    Route::get('/getSection', [RegistrationController::class, 'getSection'])->name('getSection');

    //! Download or Print PDF.......
    // Registration
    Route::get('/print_registration_form/{id}', [downloadPdfController::class, 'printRegistrationPdf'])->name('print.registration.form');
    Route::get('/download_registration_form/{id}', [downloadPdfController::class, 'downloadRegistrationPdf'])->name('download.registration.form');
    // Fees
    Route::get('/print_fees/{date}/{id}', [downloadPdfController::class, 'print_fees'])->name('print.fees');
    Route::get('/fees_download/{id}', [downloadPdfController::class, 'fees_download'])->name('fees.download');
    // Student Id Card
    Route::get('/printStudentIdCard/{id}', [downloadPdfController::class, 'printStudentIdCard'])->name('student.id.print');
    Route::get('/downloadStudentIdCard/{id}', [downloadPdfController::class, 'downloadStudentIdCard'])->name('student.id.download');
    // Student Mark sheet
    Route::get('/printMarkSheet/{exam_id}/{registration_id}', [downloadPdfController::class, 'printMarkSheet'])->name('exam.marksheet.print');
    Route::get('/downloadMarkSheet/{exam_id}/{registration_id}', [downloadPdfController::class, 'downloadMarkSheet'])->name('exam.marksheet.download');
    Route::get('/finalMarkSheet/{registration_id}', [downloadPdfController::class, 'finalMarkSheetPrint'])->name('exam.marksheet.finalMarkSheet');
    // Download or Print Blank FORM
    Route::get('/printForm', [downloadPdfController::class, 'printForm'])->name('form.print');
    Route::get('/DownloadForm', [downloadPdfController::class, 'downloadForm'])->name('form.download');


    Route::middleware(['NormalUser'])->group(function () {
        // students Registration.......
        Route::get('/registration', [RegistrationController::class, 'index'])->name('registration');
        // Registered students.....
        Route::get('/registered', [RegistrationController::class, 'registered'])->name('registered');
        // Students preview......
        Route::get('/registered_preview', [RegistrationController::class, 'preview'])->name('registered.preview');
        // Edit students......
        Route::get('/registered/{id}/edit', [RegistrationController::class, 'edit'])->name('registered.edit');
        // Edit students after registration......
        Route::get('/students/{id}/edit', [RegistrationController::class, 'editStudents'])->name('registered.editStudent');
        // Students registration.......
        Route::post('registration', [RegistrationController::class, 'registration'])->name('registration.store');
        // Update registered student......
        Route::post('/registered_update/{id}', [RegistrationController::class, 'update'])->name('registered.update');
        // Update registered student after admission......
        Route::post('/registered_update_admit_students/{id}', [RegistrationController::class, 'updateAfterAdmission'])->name('registered.updateAfterAdmission');
        // Admit students.........
        Route::post('/admit_student', [RegistrationController::class, 'admit'])->name('admit.students');
        // Delete students.........
        Route::get('/delete_student', [RegistrationController::class, 'deleteStudent'])->name('delete.students');
        // Update students status.........
        Route::post('/student_status', [StudentController::class, 'studentStatus'])->name('status.students');

        // Fees ...............
        Route::get('/fees', [FeesController::class, 'fees'])->name('fees.list');
        Route::post('/fees/store', [FeesController::class, 'store'])->name('fees.store');
        Route::get('/fees/due', [FeesController::class, 'due'])->name('fees.due');
        Route::get('/fees/paid', [FeesController::class, 'paid'])->name('fees.paid');
        Route::get('/fees/add_fees/{id}', [FeesController::class, 'add'])->name('fees.add');
        Route::get('/fees/details/{id}', [FeesController::class, 'details'])->name('fees.details');
        Route::get('/fees/status/{id}', [FeesController::class, 'status'])->name('fees.status');
        Route::get('/fees/collection_status', [FeesController::class, 'collection_status'])->name('fees.collection.status');
        // Route::get('/fees/download-pdf', [FeesController::class, 'downloadPDF'])->name('fees.due.downloadPDF');

        // Event.................
        Route::get('/event', [EventController::class, 'index'])->name('event.list');
        Route::get('/event/add', [EventController::class, 'add'])->name('event.add');
        Route::post('/event/add', [EventController::class, 'store'])->name('event.store');
        Route::post('/event/delete', [EventController::class, 'destroy'])->name('event.delete');

        // Holiday...............
        Route::get('/holiday', [HolidayController::class, 'index'])->name('holiday.list');
        Route::get('/holiday/add', [HolidayController::class, 'add'])->name('holiday.add');
        Route::post('/holiday/add', [HolidayController::class, 'store'])->name('holiday.store');
        Route::post('/holiday/delete', [HolidayController::class, 'destroy'])->name('holiday.delete');

        // Bus...............
        Route::get('/bus', [ManageBusController::class, 'index'])->name('bus.list');
        Route::get('/bus/add', [ManageBusController::class, 'add'])->name('bus.add');
        Route::post('/bus/add', [ManageBusController::class, 'store'])->name('bus.store');
        Route::get('/bus/delete', [ManageBusController::class, 'destroy'])->name('bus.destroy');

        // Route..............
        Route::get('/route', [RouteController::class, 'index'])->name('route.index');
        Route::post('/route', [RouteController::class, 'add'])->name('route.add');
        Route::get('/route/delete', [RouteController::class, 'destroy'])->name('route.destroy');



        // Bus Stops..............
        Route::get('/bus_stop', [BusStopController::class, 'index'])->name('bus_stop.index');
        Route::post('/bus_stop', [BusStopController::class, 'add'])->name('bus_stop.add');
        Route::get('/bus_stop/delete', [BusStopController::class, 'destroy'])->name('bus_stop.destroy');
        Route::get('/getStops', [BusStopController::class, 'getStops'])->name('getStops');

        // Section..............
        Route::get('/section', [SectionController::class, 'index'])->name('section.list');
        Route::get('/section/add', [SectionController::class, 'add'])->name('section.add');
        Route::post('/section/add', [SectionController::class, 'store'])->name('section.store');

        // Banner
        Route::get('/banner-setting', [BannerController::class, 'banner'])->name('admin.setting.banner');
        Route::get('/add-banner', [BannerController::class, 'banner_add'])->name('admin.setting.banner_add');
        Route::post('/add-banner', [BannerController::class, 'store'])->name('setting.banner.store');
        Route::post('/banner_delete', [BannerController::class, 'destroy'])->name('banner.delete');

        // Notice
        Route::get('/notice', [NoticeController::class, 'notice'])->name('admin.notice');
        Route::get('/add-notice', [NoticeController::class, 'notice_add'])->name('admin.notice.add');
        Route::post('/add-notice', [NoticeController::class, 'store'])->name('admin.notice.store');
        Route::get('/download_notice/{file}', [NoticeController::class, 'download_notice'])->name('download.notice');
        Route::post('/notice_delete', [NoticeController::class, 'destroy'])->name('notice.delete');

        // Department...........
        Route::get('/department', [DepartmentController::class, 'index'])->name('department.list');
        Route::get('/department/add', [DepartmentController::class, 'add'])->name('department.add');
        Route::get('/department/edit', [DepartmentController::class, 'edit'])->name('department.edit');

        // Session..............
        Route::get('/sessions', [SessionController::class, 'index'])->name('session');
        Route::post('/sessions', [SessionController::class, 'addSession'])->name('session.add');
        Route::post('/session_change', [SessionController::class, 'changeSession'])->name('session.change');

        // Classes..............
        Route::get('/classes', [ClassManagementController::class, 'index'])->name('class.list');
        Route::get('/class/add', [ClassManagementController::class, 'add'])->name('class.add');
        Route::post('/class/add', [ClassManagementController::class, 'store'])->name('class.store');
        // Route::post('/class/delete',[ClassManagementController::class,'destroy'])->name('class.delete');

        // Subjects ...............
        Route::get('/subjects', [SubjectController::class, 'index'])->name('subject.list');
        Route::get('/subjects/add', [SubjectController::class, 'add'])->name('subject.add');
        Route::post('/subjects/add', [SubjectController::class, 'store'])->name('subject.store');
        Route::post('/subjects/delete', [SubjectController::class, 'destroy'])->name('subject.delete');

        // Add Fees Types
        Route::get('/fees_types', [SetPriceController::class, 'index'])->name('fees_type.list');
        Route::get('/fees_type/add', [SetPriceController::class, 'add'])->name('fees_type.add');
        Route::post('/fees_type/add', [SetPriceController::class,'store'])->name('fees_type.store');
        Route::delete('/fees_type/delete/{id}', [SetPriceController::class, 'destroy'])->name('fees_type.delete');

        // getPrice
        Route::post('/getPrice', [SetPriceController::class, 'getPrice'])->name('getPrice');
        // getMonth
        Route::post('/getMonth', [SetPriceController::class, 'getMonth'])->name('getMonth');

         // get Exam prices
         Route::post('/getExamPrice', [SetPriceController::class, 'getExamPrice'])->name('getExamPrice');
    });

    Route::middleware(['Teacher'])->group(function () {

        // ID Card...........
        Route::get('/student_id_card', [IdCardController::class, 'index'])->name('student.idcard');
        // Exam.................
        Route::get('/exams', [ExamController::class, 'index'])->name('exam.list');
        Route::get('/exam/add', [ExamController::class, 'add'])->name('exam.add');
        Route::get('/exam_details', [ExamController::class, 'exam_details'])->name('exam.details');
        Route::post('/exam/add', [ExamController::class, 'store'])->name('exam.store');
        Route::get('/exam/delete', [ExamController::class, 'exam_delete'])->name('exam.delete');
        Route::get('/exam/publish', [ExamController::class, 'exam_publish'])->name('exam.publish');
        Route::get('/exam/unpublish', [ExamController::class, 'exam_unpublish'])->name('exam.unpublish');
        Route::get('/exam/edit/{id}', [ExamController::class, 'editExams'])->name('exam.edit');
        Route::post('/exam/edit/{id}', [ExamController::class, 'update'])->name('exam.update');


        // Manage Exam............
        Route::get('/exam/published', [ManageExamController::class, 'published'])->name('exam.published');
        Route::get('/add_marks', [ManageExamController::class, 'index'])->name('exam.index');
        Route::post('/add_marks', [ManageExamController::class, 'add_marks'])->name('exam.add_marks');
        Route::get('/result', [ManageExamController::class, 'result'])->name('exam.result');
        Route::get('/unit-test-result', [ManageExamController::class, 'UnitTestResult'])->name('exam.unitTest.result');
        Route::get('/unit-test-result/download-pdf', [ManageExamController::class, 'downloadPDF'])->name('exam.unitTestResult.downloadPDF');
        Route::get('/mark_sheet', [ManageExamController::class, 'mark_sheet'])->name('exam.mark_sheet');
        Route::get('/final_mark_sheet', [ManageExamController::class, 'final_mark_sheet'])->name('exam.final_mark_sheet');
        Route::get('/get_subject', [ManageExamController::class, 'get_exam_subject'])->name('getExamSubject');
        Route::get('/getSectionandSubject', [ExamController::class, 'getSectionandSubject'])->name('getSectionandSubject');

        // Pass out
        Route::get('/passout', [StudentController::class, 'passout'])->name('passout');
        Route::get('/getClass', [StudentController::class, 'getClass'])->name('getClass');
        Route::post('/passout/student', [StudentController::class, 'passOutByRoll'])->name('passout.student');


        // Student Leaves --------------------------------
        Route::get('/leave/pending', [LeaveController::class, 'pendingLeave'])->name('leave.pending');
        Route::get('/leave/approved', [LeaveController::class, 'approvedLeave'])->name('leave.approved');
        Route::post('/leave/delete', [LeaveController::class, 'destroy'])->name('leave.delete');
        Route::post('/leave/approve', [LeaveController::class, 'approve'])->name('leave.approve');
        Route::get('/leave/reject', [LeaveController::class, 'reject'])->name('leave.reject');
        Route::post('/leave/rejectLeave', [LeaveController::class, 'rejectLeave'])->name('leave.rejectLeave');
    });


    Route::middleware(['Admin'])->group(function () {
        // Teachers ..............
        Route::get('/teachers', [TeacherController::class, 'index'])->name('teacher.list');
        Route::get('/teacher/create', [TeacherController::class, 'create'])->name('teacher.create');
        Route::post('/teacher/store', [TeacherController::class, 'store'])->name('teacher.store');
        Route::post('/teacher/delete', [TeacherController::class, 'destroy'])->name('teacher.delete');
        Route::get('/teacher/profile/{id}', [TeacherController::class, 'profile'])->name('teacher.profile');
    });


    Route::middleware('SuperAdmin')->group(function () {
        // User Management.............
        Route::get('/user', [UserManagementController::class, 'index'])->name('user.list');
        Route::get('/user/add', [UserManagementController::class, 'add'])->name('user.add');
        // Route::post('/user/store', [UserManagementController::class, 'store'])->name('user.store');
        Route::post('/user/store', [AddUserController::class, 'store'])->name('user.store');
        Route::get('/user/edit/{id}', [UserManagementController::class, 'edit'])->name('user.edit');
        Route::post('/user/update', [UserManagementController::class, 'update'])->name('user.update');
        Route::get('/user/delete', [UserManagementController::class, 'destroy'])->name('user.destroy');


        // Settings .............
        Route::get('/setting', [SettingController::class, 'index'])->name('admin.setting');
        Route::post('/setting', [SettingController::class, 'store'])->name('admin.setting.store');
        Route::get('/configure', [SettingController::class, 'configure'])->name('admin.configure');
        //! Resources ........
        Route::resource('committee', CommitteeController::class);

        // Libraries ........
        Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
        Route::get('/books-create', [LibraryController::class, 'create'])->name('library.create');
        Route::get('/books-type', [LibraryController::class, 'create_type'])->name('library.create_type');
        Route::post('/books-create', [LibraryController::class, 'store'])->name('library.store');
        Route::post('/books-type', [LibraryController::class, 'store_type'])->name('library.store.type');
        Route::get('/getSubject', [LibraryController::class, 'getSubject'])->name('getSubject');
        Route::get('/library/edit/{id}', [LibraryController::class, 'edit'])->name('library.edit');
        Route::post('/library/update', [LibraryController::class, 'update'])->name('library.update');
        Route::get('/library/sell', [LibraryController::class, 'sell'])->name('library.sell');
        Route::post('/library/store_sale', [LibraryController::class, 'store_sale'])->name('library.store_sale');
        // Get Students after selecting class......
        Route::get('/getStudents', [LibraryController::class, 'getStudents'])->name('getStudents');
        Route::get('/getBooks', [LibraryController::class, 'getBooks'])->name('getBooks');
        Route::get('/getBooksDetails', [LibraryController::class, 'getBooksDetails'])->name('getBooksDetails');
        // Sales details
        Route::get('/sales-details',[LibraryController::class, 'salesDetails'])->name('salesDetails');
        Route::get('/sale-details',[LibraryController::class, 'saleDetails'])->name('sell.details');
    });
});

//! Migrate the tables in live server
Route::get('/migrate', function () {
    try {
        Artisan::call('migrate', [
            '--force' => true,
        ]);
        return back()->with('success', 'Migration successful!');
    } catch (\Exception $e) {
        return back()->with('error', 'Migration failed: ' . $e->getMessage());
    }
});

// Route cache clearing
Route::get('/clear-route-cache', function () {
    try {
        Artisan::call('route:clear');
        return back()->with('success', 'Route cache cleared!');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to clear route cache: ' . $e->getMessage());
    }
});
// Clear configaration cache
Route::get('/clear-config-cache', function () {
    try {
        Artisan::call('config:clear');
        return back()->with('success', 'Config cache cleared!');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to clear config cache: ' . $e->getMessage());
    }
});
// Application configuration cache
Route::get('/clear-application-cache', function () {
    try {
        Artisan::call('cache:clear');
        return back()->with('success', 'Application cache cleared!');
    } catch (\Exception $e) {
        return back()->with('error', 'Failed to clear application cache: ' . $e->getMessage());
    }
});


// Storage link
Route::get('/storage-link', function () {
    // check if the storage folder already linked;
    if (File::exists(public_path('storage'))) {
        // removed the existing symbolic link
        File::delete(public_path('storage'));

        //Regenerate the storage link folder
        try {
            Artisan::call('storage:link');
            session()->flash('success', 'Successfully storage linked.');
            return redirect()->back();
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
            return redirect()->back();
        }
    } else {
        try {
            Artisan::call('storage:link');
            session()->flash('success', 'Successfully storage linked.');
            return redirect()->back();
        } catch (\Exception $exception) {
            session()->flash('error', $exception->getMessage());
            return redirect()->back();
        }
    }
});

Route::get('/run-scheduler', function () {
    try {
        Artisan::call('schedule:run');
        session()->flash('success', 'Attendance command executed successfully!');
        return redirect()->back();
    } catch (\Exception $e) {
        session()->flash('success', 'Failed to execute attendance command: ' . $e->getMessage());
        return redirect()->back();
    }
});



// Route::get('/test-firebase-token', function () {
//     try {
//         $token = getFirebaseAccessToken();
//         return response()->json([
//             'status' => 'success',
//             'token' => $token
//         ]);
//     } catch (\Exception $e) {
//         return response()->json([
//             'status' => 'error',
//             'message' => $e->getMessage()
//         ], 500);
//     }
// });
