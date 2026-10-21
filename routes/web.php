<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Portal\PortalAuthController;
use App\Livewire\Portal\EmployeePortal;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApplicationController::class, 'index'])->name('jobs.index');
Route::get('/jobs', [ApplicationController::class, 'index'])->name('jobs.list');
Route::get('/jobs/{job}/apply', [ApplicationController::class, 'showJobForm'])->name('jobs.apply');
Route::post('/jobs/{job}/apply', [ApplicationController::class, 'submitJobForm'])->name('jobs.submit');

// Employee Self-Service (ESS) Portal Routes
Route::get('/login', fn () => redirect()->route('portal.login'))->name('login');

Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login', [PortalAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [PortalAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [PortalAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/', EmployeePortal::class)->name('dashboard');
    });
});

// Shortcut redirect for /ess
Route::get('/ess', fn () => redirect()->route('portal.dashboard'));

// Keep old route to redirect or just show jobs
Route::get('/apply', function () {
    return redirect()->route('jobs.index');
});
