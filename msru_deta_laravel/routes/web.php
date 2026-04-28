<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\QAController;
use App\Http\Controllers\AdminQAController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminAssessmentController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AuthController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register/send-otp', [App\Http\Controllers\OTPController::class, 'sendOtp'])->name('register.send_otp');

// Forgot Password Routes
Route::get('/forgot-password', [App\Http\Controllers\ForgotPasswordController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/forgot-password/send-otp', [App\Http\Controllers\ForgotPasswordController::class, 'sendOtp'])->name('password.send_otp');
Route::post('/forgot-password/reset', [App\Http\Controllers\ForgotPasswordController::class, 'resetPassword'])->name('password.reset.submit');

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint.index');
Route::post('/complaint', [ComplaintController::class, 'store'])->name('complaint.store')->middleware('throttle:5,1');
Route::get('/assessment', [AssessmentController::class, 'index'])->name('assessment.index');
Route::post('/assessment', [AssessmentController::class, 'store'])->name('assessment.store')->middleware('throttle:5,1');
Route::get('/request', [RequestController::class, 'index'])->name('request.index');

// QA Public Routes
Route::get('/qa', [QAController::class, 'index'])->name('qa.index');
Route::post('/qa', [QAController::class, 'store'])->name('qa.store')->middleware('throttle:5,1');
Route::post('/qa/{id}/destroy', [QAController::class, 'destroy'])->name('qa.user_destroy');

// Admin Primary Routes
Route::name('admin.')->prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/edpex/export', [AdminController::class, 'exportEdPEx'])->name('edpex.export');
    Route::get('/edpex/generate', [AdminController::class, 'generateEdPEx'])->name('edpex.generate');

    // Admin User Routes
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::post('/store', [AdminUserController::class, 'store'])->name('store');
        Route::post('/{id}/update', [AdminUserController::class, 'update'])->name('update');
        Route::post('/{id}/destroy', [AdminUserController::class, 'destroy'])->name('destroy');
    });

    // Admin Banner Routes
    Route::prefix('banners')->name('banners.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminBannerController::class, 'index'])->name('index');
        Route::post('/store', [App\Http\Controllers\AdminBannerController::class, 'store'])->name('store');
        Route::post('/{id}/update', [App\Http\Controllers\AdminBannerController::class, 'update'])->name('update');
        Route::post('/{id}/destroy', [App\Http\Controllers\AdminBannerController::class, 'destroy'])->name('destroy');
    });

    // Admin Assessment Routes
    Route::prefix('assessment')->name('assessment.')->group(function () {
        Route::get('/', [AdminAssessmentController::class, 'index'])->name('index');
        Route::post('/question/store', [AdminAssessmentController::class, 'storeQuestion'])->name('question.store');
        Route::post('/question/{id}/update', [AdminAssessmentController::class, 'updateQuestion'])->name('question.update');
        Route::post('/question/{id}/destroy', [AdminAssessmentController::class, 'destroyQuestion'])->name('question.destroy');
        Route::get('/responses', [AdminAssessmentController::class, 'responses'])->name('responses');
        Route::post('/responses/{id}/destroy', [AdminAssessmentController::class, 'destroyResponse'])->name('responses.destroy');
        Route::get('/export', [AdminAssessmentController::class, 'exportExcel'])->name('export');
    });

    // Admin Complaint Routes
    Route::prefix('complaints')->name('complaints.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminComplaintController::class, 'index'])->name('index');
        Route::post('/{id}/update', [App\Http\Controllers\AdminComplaintController::class, 'update'])->name('update');
        Route::post('/{id}/destroy', [App\Http\Controllers\AdminComplaintController::class, 'destroy'])->name('destroy');
    });

    // Admin Request Routes
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminRequestController::class, 'index'])->name('index');
        Route::post('/{id}/update', [App\Http\Controllers\AdminRequestController::class, 'update'])->name('update');
        Route::post('/{id}/destroy', [App\Http\Controllers\AdminRequestController::class, 'destroy'])->name('destroy');
    });

    // Admin Profile & Password Routes
    Route::get('/profile/password', [AdminProfileController::class, 'showChangePasswordForm'])->name('profile.password.index');
    Route::post('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Update Request Store Route
Route::post('/request', [RequestController::class, 'store'])->name('request.store')->middleware('throttle:5,1');

// Admin QA Routes
Route::prefix('admin/qa')->name('admin.qa.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminQAController::class, 'index'])->name('index');
    Route::post('/faq/store', [AdminQAController::class, 'storeFaq'])->name('faq.store');
    Route::post('/faq/update', [AdminQAController::class, 'updateFaq'])->name('faq.update');
    Route::post('/faq/destroy', [AdminQAController::class, 'destroyFaq'])->name('faq.destroy');
    
    Route::post('/question/status', [AdminQAController::class, 'updateStatus'])->name('question.status');
    Route::post('/question/reply', [AdminQAController::class, 'replyQuestion'])->name('question.reply');
    Route::post('/question/destroy', [AdminQAController::class, 'destroyQuestion'])->name('question.destroy');
});
