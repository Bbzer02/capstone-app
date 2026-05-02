<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\QRLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AccessDeniedController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\InterviewController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\QRCodeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\JobListingController;
use App\Http\Controllers\QRScannerController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Client session check (outside auth to return 401 when logged out)
Route::get('/session-check', function() {
    if (auth()->check()) {
        return response()->json(['authenticated' => true]);
    }
    return response()->json(['authenticated' => false], 401);
})->name('session.check.web');

// Session expired screen with animation and timed redirect
Route::get('/session-expired', function(Request $request) {
    $area = $request->query('area', 'web');
    $loginUrl = $area === 'admin' ? route('admin.login') : route('login');
    return view('auth.session_expired', [
        'area' => $area,
        'loginUrl' => $loginUrl,
        'seconds' => 5,
    ]);
})->name('session.expired');

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
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Application routes
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::get('/applications/{application}/edit-documents', [ApplicationController::class, 'editDocuments'])->name('applications.edit-documents');
    Route::put('/applications/{application}/documents', [ApplicationController::class, 'updateDocuments'])->name('applications.update-documents');
    Route::delete('/applications/{application}/documents/{type}', [ApplicationController::class, 'deleteDocument'])->name('applications.documents.delete');
    Route::delete('/applications/{application}/documents/additional/{index}', [ApplicationController::class, 'deleteAdditionalDocument'])->name('applications.documents.additional.delete');
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    
    // Document download routes
    Route::get('/documents/resume/{applicant}', [DocumentController::class, 'downloadResume'])->name('documents.resume');
    Route::get('/documents/cover-letter/{applicant}', [DocumentController::class, 'downloadCoverLetter'])->name('documents.cover-letter');
    Route::get('/documents/transcript/{applicant}', [DocumentController::class, 'downloadTranscript'])->name('documents.transcript');
    Route::get('/documents/certificate/{applicant}', [DocumentController::class, 'downloadCertificate'])->name('documents.certificate');
    Route::get('/documents/portfolio/{applicant}', [DocumentController::class, 'downloadPortfolio'])->name('documents.portfolio');
    Route::get('/documents/additional/{applicant}/{index}', [DocumentController::class, 'downloadAdditionalDocument'])->name('documents.additional');
    
    // Chat routes for applicants
    Route::get('/chat/{applicant}/{job}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/new-messages', [ChatController::class, 'getNewMessages'])->name('chat.new-messages');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');

    // QR Login routes (public - for scanning)
    Route::get('/qr/scan/{token}', [QRLoginController::class, 'showScanPage'])->name('qr.scan');
    Route::post('/qr/verify/{token}', [QRLoginController::class, 'verifyToken'])->name('qr.verify');
    Route::get('/qr/check/{token}', [QRLoginController::class, 'checkToken'])->name('qr.check');
    Route::post('/qr/generate', [QRLoginController::class, 'generateToken'])->name('qr.generate');
    Route::post('/qr/scan-admin', [AdminLoginController::class, 'scanQR'])->name('qr.scan-admin');

    // Session check endpoint for AJAX polling (must be OUTSIDE auth middleware)
    Route::get('session-check', function() {
        if (auth()->guard('admin')->check()) {
            return response()->json(['authenticated' => true]);
        }
        return response()->json(['authenticated' => false], 401);
    })->name('session.check');

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
        Route::get('applicants/{applicant}/status', function($applicant) {
            return redirect()->route('admin.applicants.show', $applicant);
        });
        Route::put('applicants/{applicant}/status', [ApplicantController::class, 'updateStatus'])
            ->name('applicants.update-status');
            
        // Interviews routes
        Route::get('interviews/create/{applicant}', [InterviewController::class, 'create'])->name('interviews.create');
        Route::post('interviews/{applicant}', [InterviewController::class, 'store'])->name('interviews.store');
        Route::delete('interviews/{interview}', [InterviewController::class, 'destroy'])->name('interviews.destroy');
        
        // Chat routes for admin
        Route::get('chat', [AdminChatController::class, 'index'])->name('chat.index');
        Route::get('chat/{applicant}/{job}', [AdminChatController::class, 'show'])->name('chat.show');
        Route::post('chat/send', [AdminChatController::class, 'sendMessage'])->name('chat.send');
        Route::post('chat/new-messages', [AdminChatController::class, 'getNewMessages'])->name('chat.new-messages');

        // Admin profile
        Route::get('profile', [AdminProfileController::class, 'show'])->name('profile.show');
        Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::get('profile/download-qr', [AdminProfileController::class, 'downloadQR'])->name('profile.download-qr');
        
        // Admin document download routes
        Route::get('documents/resume/{applicant}', [DocumentController::class, 'downloadResume'])->name('documents.resume');
        Route::get('documents/cover-letter/{applicant}', [DocumentController::class, 'downloadCoverLetter'])->name('documents.cover-letter');
        Route::get('documents/transcript/{applicant}', [DocumentController::class, 'downloadTranscript'])->name('documents.transcript');
        Route::get('documents/certificate/{applicant}', [DocumentController::class, 'downloadCertificate'])->name('documents.certificate');
        Route::get('documents/portfolio/{applicant}', [DocumentController::class, 'downloadPortfolio'])->name('documents.portfolio');
        Route::get('documents/additional/{applicant}/{index}', [DocumentController::class, 'downloadAdditionalDocument'])->name('documents.additional');

        // QR Codes management
        Route::get('qr-codes', [QRCodeController::class, 'index'])->name('qr-codes.index');
        Route::get('qr-codes/{user}', [QRCodeController::class, 'show'])->name('qr-codes.show');
        Route::get('qr-codes/{user}/download', [QRCodeController::class, 'download'])->name('qr-codes.download');
        Route::post('qr-codes/{user}/generate', [QRCodeController::class, 'generate'])->name('qr-codes.generate');
        Route::post('qr-codes/generate-all', [QRCodeController::class, 'generateAll'])->name('qr-codes.generate-all');

    });
});



Route::get('/unauthorized-admin', function () {
    return view('auth.unauthorized');
})->name('unauthorized.admin');

// Access Denied Route

// QR Scanner Routes (public - for employee login)
Route::get('/qr-scanner', [QRScannerController::class, 'index'])->name('qr.scanner');
Route::post('/qr-scan', [QRScannerController::class, 'scan'])->name('qr.scan');

// Job Routes
Route::get('/jobs', [JobListingController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobListingController::class, 'show'])->name('jobs.show');
Route::post('/jobs/{job}/apply', [JobListingController::class, 'apply'])->name('jobs.apply')->middleware('auth');