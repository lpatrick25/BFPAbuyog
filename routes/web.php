<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationStatusController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\FsicController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\MarshallController;
use App\Http\Controllers\navigation\AdminController;
use App\Http\Controllers\navigation\ClientController as NavigationClientController;
use App\Http\Controllers\navigation\InspectorController as NavigationInspectorController;
use App\Http\Controllers\navigation\MarshallController as NavigationMarshallController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/e-FSIC/{fsicNo?}', function ($fsicNo = null) {
    return view('efsic', compact('fsicNo'));
});

Route::get('/fsic_no/{fsicNo}', function ($fsicNo) {
    $fsicNo = Crypt::decryptString($fsicNo);  // Use consistent variable case
    return redirect('/e-FSIC/' . $fsicNo);
});

Route::get('/establishment', [AppController::class, 'mapping']);

// =============================
// AUTHENTICATION ROUTES
// =============================
Route::middleware('guest')->group(function () {
    Route::view('/signin', 'signin')->name('signin');
    Route::view('/signup', 'signup')->name('signup');
    Route::view('/recover', 'recover')->name('recover');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/register', [RegistrationController::class, 'register'])->name('register');
    Route::post('/password/email', [ResetPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/password/reset/{token}', function ($token) {
        return view('password-reset', ['token' => $token]);
    })->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You have been logged out.');
    })->name('logout');
});

// =============================
// EMAIL VERIFICATION ROUTES
// =============================
Route::middleware('auth')->group(function () {
    Route::get('/email/verification', function () {
        $user = Auth::user();

        if ($user && $user->email_verified_at) {
            return redirect()->route('signin');
        }

        return view('verification');
    })->name('verification.view');

    Route::view('/email/verified', 'verified')->name('verified.view');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $user = User::findOrFail($request->id);

        if (!hash_equals((string) $request->hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::login($user);
        return redirect('/')->with('verified', true);
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->notify(new VerifyEmailNotification());
        return response()->json(['message' => 'Verification email sent!'], 200);
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// =============================
// ADMIN ROUTES
// =============================
Route::prefix('admin')->middleware('auth')->controller(AdminController::class)->group(function () {
    Route::get('dashboard', 'dashboards')->name('admin.dashboard');
    Route::get('clientList', 'clients')->name('client.list');
    Route::get('marshallList', 'marshalls')->name('marshall.list');
    Route::get('inspectorList', 'inspectors')->name('inspector.list');
    Route::get('userList', 'users')->name('user.list');
    Route::get('mapping', 'mapping')->name('admin.mapping');
    Route::get('establishment/{sessionID}/show', [NavigationMarshallController::class, 'showEstablishment'])->name('admin.establishmentMapping');

    Route::get('addClient', 'addClient')->name('client.add');
    Route::get('addMarshall', 'addMarshall')->name('marshall.add');
    Route::get('addInspector', 'addInspector')->name('inspector.add');

    Route::get('client/{sessionID}/edit', 'editClient')->name('client.edit');
    Route::get('marshall/{sessionID}/edit', 'editMarshall')->name('marshall.edit');
    Route::get('inspector/{sessionID}/edit', 'editInspector')->name('inspector.edit');

    Route::post('{id}/generate-session', 'generateSessionToken')->name('adminToken.session');
});

// =============================
// CLIENT ROUTES
// =============================
Route::prefix('client')->middleware('auth')->controller(NavigationClientController::class)->group(function () {
    Route::get('dashboard', 'dashboards')->name('client.dashboard');
    Route::get('establishmentList', 'establishments')->name('establishment.list');
    Route::get('applicationList', 'applications')->name('application.list');
    Route::get('scheduleList', 'schedules')->name('client.schedule');
    Route::get('mapping', 'mapping')->name('client.mapping');
    Route::get('fsicList', 'fsic')->name('client.fsic');

    Route::get('addEstablishment', 'addEstablishment')->name('establishment.add');
    Route::get('addApplication', 'addApplication')->name('application.add');

    Route::get('establishment/{sessionID}/edit', 'editEstablishment')->name('establishment.edit');
    Route::get('establishment/{sessionID}/show', 'showEstablishment')->name('establishment.show');

    Route::post('{sessionID}/generate-session', 'generateSessionToken')->name('clientToken.session');

    Route::get('getEstablishmentApplication/{establishmentId}', 'getEstablishmentApplication')->name('establishment.application');
});

// =============================
// MARSHALL ROUTES
// =============================
Route::prefix('marshall')->middleware('auth')->controller(NavigationMarshallController::class)->group(function () {
    Route::get('dashboard', 'dashboards')->name('marshall.dashboard');
    Route::get('mapping', 'mapping')->name('marshall.mapping');
    Route::get('establishment/{sessionID}/show', 'showEstablishment')->name('marshall.establishmentMapping');
    Route::get('establishmentList', 'establishments')->name('marshall.establishments');
    Route::get('applicantList', 'applicants')->name('applicant.list');
    Route::get('scheduleList', 'schedule')->name('schedule.list');
    Route::get('fsicList', 'fsic')->name('fsic.list');

    Route::get('getApplication/{application}', 'getApplication')->name('getApplication.list');

    Route::post('{sessionID}/generate-session', 'generateSessionToken')->name('marshallToken.session');
});

// =============================
// INSPECTOR ROUTES
// =============================
Route::prefix('inspector')->middleware('auth')->controller(NavigationInspectorController::class)->group(function () {
    Route::get('dashboard', 'dashboards')->name('inspector.dashboard');
    Route::get('scheduleList', 'schedule')->name('schedule.inspection');
    Route::get('mapping', 'mapping')->name('inspector.mapping');
    Route::get('establishment/{sessionID}/show', 'showEstablishment')->name('inspector.establishmentMapping');

    Route::post('{sessionID}/generate-session', 'generateSessionToken')->name('inspectorToken.session');
});

// =============================
// RESOURCE ROUTES
// =============================
Route::resources([
    'clients' => ClientController::class,
    'marshalls' => MarshallController::class,
    'inspectors' => InspectorController::class,
    'users' => UserController::class,
    'establishments' => EstablishmentController::class,
    'applications' => ApplicationController::class,
    'applicationsStatus' => ApplicationStatusController::class,
    'schedules' => ScheduleController::class,
    'fsics' => FsicController::class,
]);

// =============================
// MAP & FSIC ROUTES
// =============================
Route::get('/load-map-view', function (Request $request) {
    $validated = $request->validate([
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    $location = [
        'latitude' => $validated['latitude'] ?? 0,
        'longitude' => $validated['longitude'] ?? 0,
    ];

    return view('pages.map', compact('location'));
})->name('loadMap');



Route::post('/store-subscription', [PushNotificationController::class, 'storeSubscription']);

Route::get('search-FSIC', [AppController::class, 'searchFSIC']);

Route::post('/updateProfile', [ProfileController::class, 'updateProfile'])->name('update.profile');
Route::put('/users/{id}/toggle-status', [ProfileController::class, 'toggleStatus'])->name('users.toggle-status');
