<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\SmsRequest;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EstablishmentController;

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
Route::post('/login', [LoginController::class, 'login']);
Route::get('/ping', function () {
    return response()->json(['pong' => true], 200);
});

Route::prefix('clients')->group(function() {
    Route::get('', [ClientController::class, 'index']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/sms-requests', function () {
        return SmsRequest::where('status', 'pending')->get();
    });

    Route::put('/sms-requests/{id}', function ($id) {
        $sms = SmsRequest::find($id);
        if ($sms) {
            $sms->status = 'sent';
            $sms->save();
            return response()->json(['message' => 'SMS status updated']);
        }
        return response()->json(['message' => 'SMS not found'], 404);
    });

    Route::prefix('establishments')->group(function() {
        Route::get('', [EstablishmentController::class, 'index']);
        Route::post('', [EstablishmentController::class, 'store']);
        Route::get('{establishment}', [EstablishmentController::class, 'show']);
        Route::put('{establishment}', [EstablishmentController::class, 'update']);
        Route::delete('{establishment}', [EstablishmentController::class, 'destroy']);
    });

});
