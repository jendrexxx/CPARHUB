<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\Employees;
use App\Livewire\System\Setup;
use App\Livewire\User\UserDashboard;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\System\Users;
use App\Livewire\User\Cpar\CparRequestForm;
use App\Livewire\User\Result\ResultRequestForm;
use App\Models\cpar_request_forms;

Route::redirect('/', '/login');

Volt::route('/login', 'auth.login')
    ->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('user_dashboard', UserDashboard::class)->name('user_dashboard');
    Route::get('/cpar-request-form', CparRequestForm::class)
        ->name('user.cpar_request_form');
    Route::get('/result-request-form', ResultRequestForm::class)
        ->name('user.result.result_request_form');
});

Route::middleware(['auth', 'verified', 'role:SUPER-ADMIN'])->group(function () {
    Route::get('admin_dashboard', AdminDashboard::class)->name('admin_dashboard');
    Route::get('system_setup', Setup::class)->name('system_setup');
    Route::get('employees', Employees::class)->name('employees');
    Route::get('/users', Users::class)
        ->name('users');
});

require __DIR__ . '/auth.php';
