<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\LaravelLogController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MemberController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Customer QR Menu Route
Route::get('/menu', \App\Livewire\Customer\QrMenu::class)->name('customer.menu');

// Admin Authentication routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Public routes (login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');

        // OTP routes
        Route::get('/login/otp', [AuthController::class, 'showOtpForm'])->name('login.otp');
        Route::post('/login/otp', [AuthController::class, 'verifyOtp'])->name('login.otp.post');
        Route::post('/login/otp/resend', [AuthController::class, 'resendOtp'])->name('login.otp.resend');
    });

    // Protected admin routes
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Resource routes
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::middleware(['permission:view-permissions'])->group(function () {
            Route::resource('permissions', PermissionController::class);
        });

        // POS Kasir Route
        Route::middleware(['permission:view-pos'])->group(function () {
            Route::get('/pos', \App\Livewire\Admin\Pos::class)->name('pos');
            Route::get('/pos/print/{id}', [AdminController::class, 'printPos'])->name('pos.print');
        });
        Route::middleware(['permission:view-reports'])->group(function () {
            Route::get('/reports', App\Livewire\Admin\Report::class)->name('reports.index');
        });

        // Activity Logs routes
        Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);

        // Laravel Logs routes (Developer / Superadmin only)
        Route::middleware(['permission:view-laravel-logs'])->group(function () {
            Route::get('laravel-logs', [LaravelLogController::class, 'index'])->name('laravel-logs.index');
            Route::get('laravel-logs/{fileName}', [LaravelLogController::class, 'show'])->name('laravel-logs.show');
            Route::delete('laravel-logs/{fileName}/clear', [LaravelLogController::class, 'clear'])->name('laravel-logs.clear');
            Route::delete('laravel-logs/{fileName}', [LaravelLogController::class, 'destroy'])->name('laravel-logs.destroy');
        });

        // Settings routes
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        // Backups route
        Route::get('backups', \App\Livewire\Admin\BackupManager::class)->name('backups.index');

        // Profile routes
        Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
        Route::put('profile/update', [\App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::put('profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('profile/check-password', [\App\Http\Controllers\ProfileController::class, 'checkPassword'])->name('profile.check-password');

        // Test Error Pages (only in non-production)
        if (app()->environment(['local', 'staging', 'development'])) {
            Route::get('test-error/{code}', function ($code) {
                $allowedCodes = [404, 500, 403, 419, 503];
                if (!in_array($code, $allowedCodes)) {
                    abort(404, 'Error code not found');
                }
                abort((int)$code, 'Test error page');
            })->name('test.error');
        }
    });
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'web'])->group(function () {
    // Category routes
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('promos', \App\Http\Controllers\PromoController::class);
    // Product routes
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    // Charge Setting routes
    Route::resource('charge_settings', \App\Http\Controllers\ChargeSettingController::class);
    // Order routes
    Route::resource('orders', \App\Http\Controllers\OrderController::class);
    // Order Item routes
    Route::resource('order_items', \App\Http\Controllers\OrderItemController::class);
    // Order Charge routes
    Route::resource('order_charges', \App\Http\Controllers\OrderChargeController::class);
    // Dining Table routes
    Route::get('dining_tables/print-all-qr', [\App\Http\Controllers\DiningTableController::class, 'printAllQr'])->name('dining_tables.print_all_qr');
    Route::resource('dining_tables', \App\Http\Controllers\DiningTableController::class);
    Route::get('dining_tables/{dining_table}/print-qr', [\App\Http\Controllers\DiningTableController::class, 'printQr'])->name('dining_tables.print_qr');
    // Cash Opname routes
        Route::resource('cash_opnames', \App\Http\Controllers\CashOpnameController::class);
        // [ADMIN_ROUTES_MARKER]
});
require __DIR__.'/test.php';

require __DIR__.'/test.php';