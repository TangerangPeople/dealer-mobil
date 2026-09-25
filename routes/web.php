<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\VehicleImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store'])
            ->middleware('throttle:admin-login')
            ->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('vehicles', VehicleController::class)->except('show');
        Route::post('vehicles/{vehicle}/images', [VehicleImageController::class, 'store'])->name('vehicles.images.store');
        Route::put('vehicles/{vehicle}/images/{vehicleImage}', [VehicleImageController::class, 'update'])->name('vehicles.images.update');
        Route::delete('vehicles/{vehicle}/images/{vehicleImage}', [VehicleImageController::class, 'destroy'])->name('vehicles.images.destroy');
    });
});
