<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\ForceJson;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});
*/


Route::get('/', function () {
    return response()->json(['message' => 'Hello world!']);
});

Route::group(
    [
        'middleware' => 'api',
        'prefix' => 'auth'
    ],
    function ($router) {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    }
);

Route::group(
    [
        'middleware' => ['jwt', 'api'],
        'prefix' => 'auth'
    ],
    function () {
        Route::get('/user', [AuthController::class, 'getUser']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::put('/user', [AuthController::class, 'updateUser']);
        Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
    }
);

Route::group([
    'middleware' => ['jwt', 'api']
], function () {

    Route::get('/events/{event}/participants', [App\Http\Controllers\API\EventController::class, 'participants']);
    Route::get('/events/{event}/participants/{volunteer}', [App\Http\Controllers\API\EventController::class, 'participants_show']);

    Route::apiResource('/events', App\Http\Controllers\API\EventController::class);

    Route::apiResource('/organizations', App\Http\Controllers\API\OrganizationController::class);

    Route::apiResource('/event_categories', App\Http\Controllers\API\EventCategoryController::class);

    Route::apiResource('/users', App\Http\Controllers\API\UserController::class);

    Route::apiResource('/ratings', App\Http\Controllers\API\RatingController::class);

    Route::get('/volunteers/{volunteer}/applications', 'App\Http\Controllers\API\VolunteerController@applications');

    Route::apiResource('/volunteers', App\Http\Controllers\API\VolunteerController::class);

    Route::apiResource('/applications', App\Http\Controllers\API\ApplicationController::class);

    Route::apiResource('/attendances', App\Http\Controllers\API\AttendanceController::class);

    Route::get('/user/{user}/profile_photo', 'App\Http\Controllers\API\UserController@profile_photo')->name('user.profile_photo');
});
