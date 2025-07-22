<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DashboardController;



use App\Http\Controllers\Auth\{
    TwoFactorAuthController,
    ProfileController,
    ImpersonationController,
};
use App\Http\Controllers\Admin\{
    SettingController,
    UserController,
    RoleController,
    PermissionController,
    ActivityLogController,
    BackupController,
    JobController,
};

// 🔐 Auth Routes
Auth::routes();

// 🏠 Public Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// 🧭 Dashboard & Admin (Requires auth, email verification, and 2FA)
Route::middleware(['auth', 'verified', '2fa'])->group(function () {

    // 📊 Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // ⚙️ Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::get('/{setting}/edit', [SettingController::class, 'edit'])->name('edit');
        Route::put('/{setting}', [SettingController::class, 'update'])->name('update');
        Route::put('/', [SettingController::class, 'bulkUpdate'])->name('bulk-update');
    });

    // 📜 Activity Logs
    Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::delete('/delete-selected', [ActivityLogController::class, 'destroySelected'])->name('deleteSelected');
        Route::delete('/clear', [ActivityLogController::class, 'clearAll'])->name('clear');
    });

    // 👥 User Management
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');

    // 🔐 Roles & Permissions
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::resource('permissions', PermissionController::class)->except(['show']);

    // 💾 Backups
    Route::resource('backups', BackupController::class);
    Route::get('/backups/{backup}/download', [BackupController::class, 'download'])->name('backups.download');
    Route::post('/backups/{backup}/restore', [BackupController::class, 'restore'])->name('backups.restore');

    Route::get('/backups/progress', function () {
        $progress = cache()->get('backup_progress_' . auth()->id(), [
            'processed' => 0,
            'total' => 1,
            'percentage' => 0,
            'timestamp' => now()
        ]);
        return response()->json($progress);
    })->name('backups.progress');

    Route::post('/backups/cancel', function () {
        cache()->forget('backup_progress_' . auth()->id());
        return response()->json(['status' => 'cancelled']);
    })->name('backups.cancel');

    // ⚙️ Job Management
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', [JobController::class, 'index'])->name('index');
        Route::get('/{id}', [JobController::class, 'show'])->name('show');
        Route::post('/{id}/retry', [JobController::class, 'retry'])->name('retry');
        Route::delete('/{id}', [JobController::class, 'destroy'])->name('destroy');
        Route::get('/stats/view', [JobController::class, 'stats'])->name('stats');
    });
});

// 👤 Profile, 2FA, and Impersonation (Requires auth & email verification)
Route::middleware(['auth', 'verified'])->group(function () {

    // 🔒 Two-Factor Authentication
    Route::prefix('2fa')->name('2fa.')->group(function () {
        Route::get('/verify', [TwoFactorAuthController::class, 'showVerifyForm'])->name('verify');
        Route::post('/verify', [TwoFactorAuthController::class, 'verify'])->name('verify.submit');
    });

    Route::prefix('two-factor')->name('two-factor.')->group(function () {
        Route::post('/enable', [TwoFactorAuthController::class, 'enable'])->name('enable');
        Route::get('/confirm-disable', [TwoFactorAuthController::class, 'showDisableConfirmation'])->name('confirm-disable');
        Route::delete('/disable', [TwoFactorAuthController::class, 'disable'])->name('disable');
    });

    // 🔐 Recovery Codes
    Route::prefix('recovery-codes')->name('recovery-codes.')->group(function () {
        Route::get('/', [TwoFactorAuthController::class, 'showRecoveryCodes'])->name('show');
        Route::post('/generate', [TwoFactorAuthController::class, 'generateNewRecoveryCodes'])->name('generate');
    });

    // 👤 Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password');
    });

    // 👤 Impersonation
    Route::post('/impersonate/{user}', [ImpersonationController::class, 'start'])->name('impersonate.start');
    Route::post('/leave-impersonation', [ImpersonationController::class, 'stop'])->name('impersonate.stop');
});

// 🔔 Notifications (Requires auth)
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('index');
    Route::post('/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
});
