<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AuthController, ChapterController, CourseController, ImageCourseController, LessonController, MentorController, ReviewController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');
    Route::post('/me', 'me');
    Route::post('/change-password', 'changePassword');
});

Route::controller(MentorController::class)->prefix('mentors')->group(function () {
    Route::get('/', 'index');
    Route::get('/{mentor}', 'show');
    Route::post('/', 'store');
    Route::put('/{mentor}', 'update');
    Route::delete('/{mentor}', 'destroy');
});

Route::controller(CourseController::class)->prefix('courses')->group(function () {
    Route::get('/', 'index');
    Route::get('/{course}', 'show');
    Route::post('/', 'store');
    Route::put('/{course}', 'update');
    Route::delete('/{course}', 'destroy');
});

Route::controller(ChapterController::class)->prefix('chapters')->group(function () {
    Route::get('/', 'index');
    Route::get('/{chapter}', 'show');
    Route::post('/', 'store');
    Route::put('/{chapter}', 'update');
    Route::delete('/{chapter}', 'destroy');
});

Route::controller(LessonController::class)->prefix('lessons')->group(function () {
    Route::get('/', 'index');
    Route::get('/{lesson}', 'show');
    Route::post('/', 'store');
    Route::put('/{lesson}', 'update');
    Route::delete('/{lesson}', 'destroy');
});

Route::controller(ImageCourseController::class)->prefix('image-courses')->group(function () {
    Route::post('/', 'store');
    Route::delete('/{imageCourse}', 'destroy');
});

Route::controller(ReviewController::class)->prefix('reviews')->group(function () {
    Route::post('/', 'store');
    Route::put('/{review}', 'update');
    Route::delete('/{review}', 'destroy');
});

