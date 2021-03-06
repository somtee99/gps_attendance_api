<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['prefix' => 'v1'], function () {
    Route::post('register/student', 'UserController@registerStudent');
    Route::post('register/lecturer', 'UserController@registerLecturer');
    Route::post('login', 'UserController@login');
    Route::get('handshake', 'UserController@handshake');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('user/{user_uuid}', 'UserController@getUser');
        Route::get('profile', 'UserController@getProfile');

        Route::post('course/register/{course_uuid}', 'CourseController@registerCourse');
        Route::post('courses/register', 'CourseController@registerCourses');
        Route::post('course/assign/{course_uuid}', 'CourseController@assignCourse');
        Route::delete('course/remove/{course_uuid}', 'CourseController@removeCourse');
        Route::get('courses/mine', 'CourseController@getMyCourses');
        Route::get('courses/{level?}', 'CourseController@getCourses');
        Route::post('course/create', 'CourseController@createCourse');

        Route::get('lecture/{lecture_uuid}', 'LectureController@getLecture');
        Route::post('lecture/set/{startTime}/{endTime}', 'LectureController@setLecture');
        Route::post('lecture/set/weekly/{no_of_weeks}', 'LectureController@setWeeklyLectures'); //incomplete
        Route::put('lecture/edit/{lecture_uuid}', 'LectureController@editLecture');
        Route::delete('lecture/delete/{lecture_uuid}', 'LectureController@deleteLecture');
        Route::get('lectures/next', 'LectureController@getNextLectures2'); //edited to use a getNextLectures2
        Route::get('lectures/previous', 'LectureController@getPreviousLectures');
        Route::get('lectures/all', 'LectureController@getLectures');
        Route::get('lectures/day/{date}', 'LectureController@getLecturesByDay');

        Route::post('sign/in/{lecture_uuid}', 'AttendanceController@signIn');
        Route::post('sign/out/{lecture_uuid}', 'AttendanceController@signOut');
        Route::get('lecture/{lecture_uuid}/attendees', 'AttendanceController@getAttendees');
        Route::get('lecture/{lecture_uuid}/absentees', 'AttendanceController@getAbsentees');

        Route::post('hall/{hall_uuid}/point', 'HallController@addPointToHallGeoFence');
        Route::post('hall/{hall_uuid}/points/delete', 'HallController@clearHallGeoFencePoints');
        Route::get('hall/{hall_uuid}/points', 'HallController@getHallGeoFencePoints');
        Route::get('halls', 'HallController@getHalls');

    });
});

