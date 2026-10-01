<?php

use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StaffMemberController;
use App\Http\Controllers\Admin\StaffPerformanceController;
use App\Http\Controllers\Admin\SurveyController as AdminSurveyController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\InternalSurveyAccessController;
use App\Http\Controllers\PublicServiceController;
use App\Http\Controllers\ServiceAccessController;
use App\Http\Controllers\SubmissionSuccessController;
use App\Http\Controllers\SurveyResponseController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::get('/layanan-publik', [PublicServiceController::class, 'index'])->name('public-services.index');
Route::get('/layanan-publik/{service}', [PublicServiceController::class, 'show'])->name('public-services.show');
Route::view('/pusat-informasi', 'information')->name('information.index');
Route::view('/survei', 'surveys')->name('surveys.index');
Route::get('/survei-petugas', [SurveyResponseController::class, 'create'])->name('staff-surveys.create');
Route::view('/pengaduan', 'complaint')->name('complaints.create');
Route::get('/pengaduan/berhasil', [SubmissionSuccessController::class, 'complaint'])->name('complaints.success');
Route::get('/survei-petugas/berhasil', [SubmissionSuccessController::class, 'staffSurvey'])->name('staff-surveys.success');
Route::view('/test', 'test')->name('tests.index');

Route::post('/pengaduan', [ComplaintController::class, 'store'])->middleware('throttle:10,1')->name('complaints.store');
Route::post('/survei-petugas', [SurveyResponseController::class, 'store'])->middleware('throttle:20,1')->name('survey-responses.store');
Route::post('/survei-petugas/ketersediaan', [SurveyResponseController::class, 'availability'])->middleware('throttle:30,1')->name('survey-responses.availability');
Route::post('/survei-internal/akses', [InternalSurveyAccessController::class, 'store'])->middleware('throttle:5,1')->name('internal-surveys.access');
Route::get('/survei-internal/login', [ServiceAccessController::class, 'internalSurvey'])->middleware('throttle:60,1')->name('internal-surveys.login');
Route::get('/survei-spkp-spak/akses', [ServiceAccessController::class, 'spkpSpak'])->middleware('throttle:60,1')->name('spkp-spak.access');

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::post('/admin/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/petugas/{staffMember}/performa', StaffPerformanceController::class)->name('staff.performance');
    Route::get('/petugas', [StaffMemberController::class, 'index'])->name('staff.index');
    Route::post('/petugas', [StaffMemberController::class, 'store'])->name('staff.store');
    Route::get('/petugas/{staffMember}/edit', [StaffMemberController::class, 'edit'])->name('staff.edit');
    Route::put('/petugas/{staffMember}', [StaffMemberController::class, 'update'])->name('staff.update');
    Route::patch('/petugas/{staffMember}/status', [StaffMemberController::class, 'updateStatus'])->name('staff.status');
    Route::get('/pengaduan', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/pengaduan/{complaint}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::get('/pengaduan/{complaint}/formulir', [AdminComplaintController::class, 'form'])->name('complaints.form');
    Route::get('/pengaduan/{complaint}/lampiran', [AdminComplaintController::class, 'evidence'])->name('complaints.evidence');
    Route::get('/survei', [AdminSurveyController::class, 'index'])->name('surveys.index');
    Route::get('/survei/export', [AdminSurveyController::class, 'export'])->name('surveys.export');
    Route::get('/survei/{surveyResponse}', [AdminSurveyController::class, 'show'])->name('surveys.show');
});
