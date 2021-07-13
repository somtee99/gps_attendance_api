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
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::post('/login-action', 'UserController@loginAction');
Route::post('/create-lecture-action', 'LectureController@createLectureAction');

Route::get('/register', function () {
    return view('register');
});

Route::get('/lectures', function () {
    return view('lectures.show');
});

Route::get('/lecture/create', function () {
    return view('lectures.create');
});

Route::get('/attendance/{lecture_uuid}', function () {
    return view('attendances');
});
