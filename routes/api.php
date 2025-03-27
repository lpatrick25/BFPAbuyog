<?php

use App\Http\Controllers\ApplicationAttachmentController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationStatusController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\FsicController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\MarshallController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('applications')->group(function () {
    Route::get('/', [ApplicationController::class, 'index']); // Get all Applications
    Route::get('/{id}', [ApplicationController::class, 'show']); // Get a single Application
    Route::post('/', [ApplicationController::class, 'store']); // Create an Application
    Route::put('/{id}', [ApplicationController::class, 'update']); // Update an Application
    Route::delete('/{id}', [ApplicationController::class, 'destroy']); // Delete an Application
});

Route::prefix('application-attachments')->group(function () {
    Route::post('/', [ApplicationAttachmentController::class, 'store']); // Upload an Attachment
    Route::get('/{application_id}', [ApplicationAttachmentController::class, 'index']); // Get all Attachments for an Application
    Route::get('/download/{id}', [ApplicationAttachmentController::class, 'download']); // Download an Attachment
    Route::delete('/{id}', [ApplicationAttachmentController::class, 'destroy']); // Delete an Attachment
});

Route::prefix('application-statuses')->group(function () {
    Route::post('/', [ApplicationStatusController::class, 'store']); // Create an Application Status
    Route::get('/{application_id}', [ApplicationStatusController::class, 'index']); // Get all Statuses for an Application
    Route::get('/show/{id}', [ApplicationStatusController::class, 'show']); // Get a single Application Status
    Route::put('/{id}', [ApplicationStatusController::class, 'update']); // Update an Application Status
    Route::delete('/{id}', [ApplicationStatusController::class, 'destroy']); // Delete an Application Status
});

Route::prefix('establishments')->group(function () {
    Route::post('/', [EstablishmentController::class, 'store']); // Create an Establishment
    Route::get('/', [EstablishmentController::class, 'index']); // Get all Establishments
    Route::get('/show/{id}', [EstablishmentController::class, 'show']); // Get a single Establishment
    Route::put('/{id}', [EstablishmentController::class, 'update']); // Update an Establishment
    Route::delete('/{id}', [EstablishmentController::class, 'destroy']); // Delete an Establishment
});

Route::prefix('fsics')->group(function () {
    Route::post('/', [FsicController::class, 'store']); // Create an FSIC
    Route::get('/', [FsicController::class, 'index']); // Get all FSICs
    Route::get('/show/{id}', [FsicController::class, 'show']); // Get a single FSIC
    Route::put('/{id}', [FsicController::class, 'update']); // Update an FSIC
    Route::delete('/{id}', [FsicController::class, 'destroy']); // Delete an FSIC
});

Route::prefix('inspectors')->group(function () {
    Route::get('/', [InspectorController::class, 'index']); // Get all Inspectors
    Route::get('/{id}', [InspectorController::class, 'show']); // Get a single Inspector
    Route::post('/', [InspectorController::class, 'store']); // Create a Inspector
    Route::put('/{id}', [InspectorController::class, 'update']); // Update a Inspector
    Route::delete('/{id}', [InspectorController::class, 'destroy']); // Delete a Inspector
});

Route::prefix('marshalls')->group(function () {
    Route::get('/', [MarshallController::class, 'index']); // Get all Marshalls
    Route::get('/{id}', [MarshallController::class, 'show']); // Get a single Marshall
    Route::post('/', [MarshallController::class, 'store']); // Create a Marshall
    Route::put('/{id}', [MarshallController::class, 'update']); // Update a Marshall
    Route::delete('/{id}', [MarshallController::class, 'destroy']); // Delete a Marshall
});

Route::prefix('schedules')->group(function () {
    Route::post('/', [ScheduleController::class, 'store']); // Create a Schedule
    Route::get('/', [ScheduleController::class, 'index']); // Get all Schedules
    Route::get('/show/{id}', [ScheduleController::class, 'show']); // Get a single Schedule
    Route::put('/{id}', [ScheduleController::class, 'update']); // Update a Schedule
    Route::delete('/{id}', [ScheduleController::class, 'destroy']); // Delete a Schedule
});
