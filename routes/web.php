<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\DeptHeadDashboard;
use App\Livewire\Admin\Employees;
use App\Livewire\Admin\HrHeadDashboard;
use App\Livewire\Admin\LabSupervisorDashboard;
use App\Livewire\Admin\Reports\CparMasterFile;
use App\Livewire\Admin\Reports\CparReports;
use App\Livewire\Admin\Reports\Pdf;
use App\Livewire\System\Setup;
use App\Livewire\User\UserDashboard;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\System\Users;
use App\Livewire\User\Cpar\CparRequestForm;
use App\Livewire\User\Result\ResultRequestForm;

Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Volt::route('login', 'auth.login')
    ->name('auth.login');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth', 'verified', 'session.timeout'])->group(function () {
    Route::get('dept_head_dashboard', DeptHeadDashboard::class)
        ->name('dept_head_dashboard');

    Route::get('hr-dashboard', HrHeadDashboard::class)
        ->name('hr_dashboard');

    Route::get('lab_supervisor', LabSupervisorDashboard::class)
        ->name('lab_supervisor');

    Route::get('/cpar/{assignment_id}/pdf', [Pdf::class, 'pdf'])->name('cpar.pdf');
});

Route::middleware(['auth', 'verified', 'session.timeout'])->group(function () {
    Route::get('user_dashboard', UserDashboard::class)
        ->name('user_dashboard');
    Route::get('/cpar-request-form', CparRequestForm::class)
        ->name('cpar_request_form');
    Route::get('/cpar-report', CparReports::class)
        ->name('cpar-report');
    Route::get('/cpar-master-file', CparMasterFile::class)
        ->name('cpar-master-file');
    Route::get('/result-request-form', ResultRequestForm::class)
        ->name('user.result.result_request_form');
});

Route::middleware([
    'auth',
    'verified',
    'role:SUPER-ADMIN',
    'session.timeout'
])->group(function () {

    Route::get('admin_dashboard', AdminDashboard::class)
        ->name('admin_dashboard');

    Route::get('system_setup', Setup::class)
        ->name('system_setup');

    Route::get('employees', Employees::class)
        ->name('employees');

    Route::get('/users', Users::class)
        ->name('users');
});


require __DIR__ . '/auth.php';
