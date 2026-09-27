<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ApplicationController::class, 'index'])->name('jobs.index');
Route::get('/jobs', [ApplicationController::class, 'index'])->name('jobs.list');
Route::get('/jobs/{job}/apply', [ApplicationController::class, 'showJobForm'])->name('jobs.apply');
Route::post('/jobs/{job}/apply', [ApplicationController::class, 'submitJobForm'])->name('jobs.submit');

// Keep old route to redirect or just show jobs
Route::get('/apply', function () {
    return redirect()->route('jobs.index');
});
