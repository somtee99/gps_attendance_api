<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login-action', 'UserController@loginAction');

Route::get('/register', function () {
    return view('register');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/lectures', function () {
        return view('lectures.show');
    });
    Route::post('/create-lecture-action', 'LectureController@createLectureAction');

    Route::get('/lecture/create', function () {
        return view('lectures.create');
    });

    Route::get('/attendance/{lecture_uuid}', 'AttendanceController@getAttendees2');
});

