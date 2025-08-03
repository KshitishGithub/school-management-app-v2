<?php

use App\Http\Controllers\api\authController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\HolidayController;
use App\Http\Controllers\Api\NoticeController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/banner',[BannerController::class,'index']);
Route::get('/notice',[NoticeController::class,'index']);
Route::get('/holiday',[HolidayController::class,'index']);
Route::get('/event',[EventController::class,'index']);
Route::get('/teacher/{id?}',[TeacherController::class,'index']);
Route::get('/session',[StudentController::class,'session']);
Route::get('/class',[StudentController::class,'class']);
Route::get('/section/{id}',[StudentController::class,'section']);
Route::get('/student/profile/{id?}',[StudentController::class,'index']);
// attendance for hole class students
Route::post('/student/attendance',[StudentController::class,'attendance']);
Route::post('/student/fees',[StudentController::class,'feesDetails']);
Route::post('/student/singleResult',[StudentController::class,'singleResult']);
Route::post('/student/attendance/fill',[StudentController::class,'ateendanceFill']);
Route::post('/student/attendance/view',[StudentController::class,'ateendanceView']);
Route::post('/student/login',[StudentController::class,'login']);
Route::post('/student/leave',[StudentController::class,'leave']);
Route::post('/student/leavedata',[StudentController::class,'leavedata']);
Route::post('/student/inOutAttendance',[StudentController::class,'inOutAttendance']);
Route::post('/student/appLogout',[StudentController::class,'appLogout']);
// Route::post('/teacher/login',[authController::class,'loginUser']);
