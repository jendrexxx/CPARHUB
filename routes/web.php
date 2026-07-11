<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\User\Cpar\CparRequestForm;
use App\Livewire\User\UserDashboard;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

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
    Route::get('cpar_request_form', CparRequestForm::class)->name('cpar_request_form');
});

Route::middleware(['auth', 'verified', 'role:SUPER-ADMIN'])->group(function () {
    Route::get('admin_dashboard', AdminDashboard::class)->name('admin_dashboard');
});

require __DIR__ . '/auth.php';
