<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\InterviewController;
use App\Http\Controllers\JobListingController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');

    Route::middleware(['auth:admin'])->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            
        // Jobs routes
        Route::resource('jobs', JobController::class);
        Route::post('jobs/{job}/toggle-publish', [JobController::class, 'togglePublish'])
            ->name('jobs.toggle-publish');

        // Applicants routes
        Route::get('applicants', [ApplicantController::class, 'index'])->name('applicants.index');
        Route::get('applicants/{applicant}', [ApplicantController::class, 'show'])->name('applicants.show');
        Route::put('applicants/{applicant}/status', [ApplicantController::class, 'updateStatus'])
            ->name('applicants.update-status');
            
        // Interviews routes
        Route::get('interviews/create/{applicant}', [InterviewController::class, 'create'])->name('interviews.create');
        Route::post('interviews/{applicant}', [InterviewController::class, 'store'])->name('interviews.store');
        Route::delete('interviews/{interview}', [InterviewController::class, 'destroy'])->name('interviews.destroy');
    });
});



Route::get('/unauthorized-admin', function () {
    return view('auth.unauthorized');
})->name('unauthorized.admin');

// Job Routes
Route::get('/jobs', [JobListingController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobListingController::class, 'show'])->name('jobs.show');
Route::post('/jobs/{job}/apply', [JobListingController::class, 'apply'])->name('jobs.apply')->middleware('auth');