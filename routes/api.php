<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

/**
 * Public routes
 */
Route::post('/login', [LoginController::class, 'login']);

/**
 * Auth routes
 */
Route::middleware(['auth:sanctum'])->group(function () {
    /**
     * Profile routes
     */
    Route::prefix('/user')->group(function () {

        Route::patch('/password', [PasswordController::class, 'changePassword']);
    });

    /**
     * User routes
     */
    Route::prefix('/users')->group(function () {
        Route::get('/students', [StudentController::class, 'index']);
        Route::post('/students', [StudentController::class, 'store']);
        Route::put('/students/{user}', [StudentController::class, 'update']);

        Route::get('/employees', [EmployeeController::class, 'index']);
        Route::post('/employees', [EmployeeController::class, 'store']);
        Route::put('/employees/{user}', [EmployeeController::class, 'update']);

        Route::get('/', [UserController::class, 'index']);
        Route::get('/{user}', [UserController::class, 'show']);
        Route::delete('/{user}', [UserController::class, 'destroy']);
    });

    Route::apiResource('/permissions', PermissionController::class);
    Route::apiResource('/roles', RoleController::class);

    Route::apiResource('/faculties', FacultyController::class);

    Route::get('/programs', [ProgramController::class, 'index']);
    Route::post('/faculties/{faculty}/programs', [ProgramController::class, 'store']);
    Route::get('/programs/{program}', [ProgramController::class, 'show']);
    Route::put('/faculties/{faculty}/programs/{program}', [ProgramController::class, 'update']);
    Route::delete('/programs/{program}', [ProgramController::class, 'destroy']);

    Route::apiResource('/subjects', SubjectController::class);

    Route::apiResource('/events', EventController::class)->except('create');


    Route::get('/logout', [LogoutController::class, 'logout']);
});
//Notes Section

Route::apiResource('/notes', NoteController::class);

/**
 * Fallback route
 */
Route::fallback(fn () => response(['message' => 'Requested resource does not exist'], 404));


