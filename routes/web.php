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

// مسار تحديث قاعدة البيانات الآمن (للاستضافات المشتركة و Hostinger بدون SSH)
Route::get('/system/migrate', function (\Illuminate\Http\Request $request) {
    $user = auth()->user();
    $canRun = ($user && ($user->isAdministrative() || $user->hasRole(['admin', 'Super Admin', 'مسؤول النظام', 'المدير العام']))) 
           || ($request->query('key') === 'hr2026migrate');

    if (! $canRun) {
        abort(403, 'غير مصرح لك بتشغيل هذا المسار.');
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $clearOutput = \Illuminate\Support\Facades\Artisan::output();

        return response("<!DOCTYPE html>
<html dir='rtl' lang='ar'>
<head>
    <meta charset='utf-8'>
    <title>تحديث قاعدة البيانات بنجاح</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Cairo', sans-serif; background: #f8fafc; padding: 2rem; color: #1e293b; }
        .card { max-width: 700px; margin: 0 auto; background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .badge { display: inline-flex; align-items: center; gap: 0.5rem; background: #dcfce7; color: #15803d; font-weight: bold; padding: 0.5rem 1rem; border-radius: 9999px; font-size: 0.875rem; margin-bottom: 1rem; }
        pre { background: #0f172a; color: #38bdf8; padding: 1.25rem; border-radius: 0.75rem; direction: ltr; font-size: 0.85rem; overflow-x: auto; font-family: monospace; }
        .btn { display: inline-block; background: #0284c7; color: white; text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-weight: bold; margin-top: 1rem; transition: background 0.2s; }
        .btn:hover { background: #0369a1; }
    </style>
</head>
<body>
    <div class='card'>
        <div class='badge'>✓ تم التحديث بنجاح (Database Migrated)</div>
        <h2 style='margin: 0 0 0.5rem 0; font-size: 1.5rem;'>تم إنشاء الجداول وتحديث قاعدة البيانات بنجاح!</h2>
        <p style='color: #64748b; font-size: 0.95rem; margin-bottom: 1.5rem;'>تم إنشاء جداول التعميمات والرواتب وكافة التحديثات الأخيرة بنجاح على قاعدة بيانات السيرفر.</p>
        <pre>" . e($migrateOutput . "\n" . $clearOutput) . "</pre>
        <div style='text-align: left;'>
            <a href='/admin' class='btn'>الانتقال إلى لوحة الإدارة</a>
        </div>
    </div>
</body>
</html>");
    } catch (\Throwable $e) {
        return response("<!DOCTYPE html><html dir='rtl'><body style='font-family:sans-serif;padding:2rem;background:#fef2f2;'><div style='max-width:700px;margin:auto;background:white;padding:2rem;border-radius:1rem;border:1px solid #fecaca;'><h2 style='color:#dc2626;'>فشل التحديث:</h2><pre style='background:#fee2e2;color:#991b1b;padding:1rem;border-radius:0.5rem;direction:ltr;'>" . e($e->getMessage()) . "</pre></div></body></html>", 500);
    }
});
