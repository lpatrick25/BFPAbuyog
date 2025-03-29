<?php

use App\Events\NewApplication;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationStatusController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\MarshallController;
use App\Http\Controllers\navigation\AdminController;
use App\Http\Controllers\navigation\ClientController as NavigationClientController;
use App\Http\Controllers\navigation\MarshallController as NavigationMarshallController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('signin');
})->name('signin')->middleware('guest');

Route::get('/signup', function () {
    return view('signup');
})->name('signup')->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/logout', function (Request $request) {
    Auth::logout(); // Logout the user

    // Invalidate the session and regenerate the CSRF token
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Redirect to the login page or home page
    return redirect('/')->with('success', 'You have been logged out.');
})->name('logout');

Route::post('/register', [RegistrationController::class, 'register'])->name('register');

Route::get('/email/verification', function () {
    return view('verification');
})->name('verification.view');

Route::get('/email/verified', function () {
    return view('verified');
})->name('verified.view');

Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::findOrFail($id);

    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Invalid verification link.');
    }

    Auth::login($user); // Log in the user before marking email as verified
    $user->markEmailAsVerified(); // Mark email as verified

    return redirect('/home')->with('verified', true);
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    if ($request->user()) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification email sent!'], 200);
    }
    return response()->json(['error' => 'Unauthorized'], 401);
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::prefix('admin')->middleware(['auth', 'guest'])->group(function () {
    // Route View
    Route::get('dashboard', [AdminController::class, 'dashboards'])->name('admin.dashboard');
    Route::get('clientList', [AdminController::class, 'clients'])->name('client.list');
    Route::get('marshallList', [AdminController::class, 'marshalls'])->name('marshall.list');
    Route::get('inspectorList', [AdminController::class, 'inspectors'])->name('inspector.list');
    Route::get('userList', [AdminController::class, 'users'])->name('user.list');

    // Route Add Pages
    Route::get('addClient', [AdminController::class, 'addClient'])->name('client.add');
    Route::get('addMarshall', [AdminController::class, 'addMarshall'])->name('marshall.add');
    Route::get('addInspector', [AdminController::class, 'addInspector'])->name('inspector.add');

    // Route Edit Pages
    Route::get('client/{sessionID}/edit', [AdminController::class, 'editClient'])->name('client.edit');
    Route::get('marshall/{sessionID}/edit', [AdminController::class, 'editMarshall'])->name('marshall.edit');
    Route::get('inspector/{sessionID}/edit', [AdminController::class, 'editInspector'])->name('inspector.edit');

    // Route Session
    Route::post('client/{clientId}/generate-session', [AdminController::class, 'generateSessionToken'])->name('client.session');
    Route::post('marshall/{marshallId}/generate-session', [AdminController::class, 'generateSessionToken'])->name('marshall.session');
    Route::post('inspector/{inspectorId}/generate-session', [AdminController::class, 'generateSessionToken'])->name('inspector.session');
});

Route::prefix('client')->middleware(['auth', 'guest'])->group(function () {
    // Route View
    Route::get('dashboard', [NavigationClientController::class, 'dashboards'])->name('client.dashboard');
    Route::get('establishmentList', [NavigationClientController::class, 'establishments'])->name('establishment.list');
    Route::get('applicationList', [NavigationClientController::class, 'applications'])->name('application.list');

    // Route Add Pages
    Route::get('addEstablishment', [NavigationClientController::class, 'addEstablishment'])->name('establishment.add');
    Route::get('addApplication', [NavigationClientController::class, 'addApplication'])->name('application.add');

    // Route Edit Pages
    Route::get('establishment/{sessionID}/edit', [NavigationClientController::class, 'editEstablishment'])->name('establishment.edit');

    // Route Session
    Route::post('{establishmentId}/generate-session', [NavigationClientController::class, 'generateSessionToken'])->name('establishment.session');

    // Other Route
    Route::get('getEstablishmentApplication/{establishmentId}', [NavigationClientController::class, 'getEstablishmentApplication'])->name('establishment.application');
});

Route::prefix('marshall')->middleware(['auth', 'guest'])->group(function () {
    // Route View
    Route::get('dashboard', [NavigationMarshallController::class, 'dashboards'])->name('marshall.dashboard');
    Route::get('establishmentList', [NavigationMarshallController::class, 'establishments'])->name('marshall.establishments');
    Route::get('applicantList', [NavigationMarshallController::class, 'applicants'])->name('applicant.list');
    Route::get('scheduleList', [NavigationMarshallController::class, 'schedule'])->name('schedule.list');

    // Other Route
    Route::put('changeSchedule/{applicationId}', [NavigationMarshallController::class, 'changeSchedule'])->name('schedule.change');
});

// Route Resource
Route::resource('clients', ClientController::class);
Route::resource('marshalls', MarshallController::class);
Route::resource('inspectors', InspectorController::class);
Route::resource('users', UserController::class);
Route::resource('establishments', EstablishmentController::class);
Route::resource('applications', ApplicationController::class);
Route::resource('applicationsStatus', ApplicationStatusController::class);
Route::resource('schedules', ScheduleController::class);

// Log Map Route
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
