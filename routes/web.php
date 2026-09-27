<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DealerSettingController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\VehicleImageController;
use App\Http\Controllers\Public\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/tentang', [PublicPageController::class, 'about'])->name('about');
Route::get('/mobil', [PublicPageController::class, 'vehiclesIndex'])->name('vehicles.index');
Route::get('/mobil/{vehicle}', [PublicPageController::class, 'vehicleShow'])->name('vehicles.show');
Route::get('/promo', [PublicPageController::class, 'promotionsIndex'])->name('promotions.index');
Route::get('/promo/{promotion}', [PublicPageController::class, 'promotionShow'])->name('promotions.show');
Route::get('/galeri', [PublicPageController::class, 'galleryIndex'])->name('gallery.index');
Route::get('/kontak', [PublicPageController::class, 'contact'])->name('contact');
Route::get('/simulasi-kredit', [PublicPageController::class, 'creditSimulation'])->name('credit-simulation');

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
        Route::resource('promotions', PromotionController::class)->except('show');
        Route::resource('sales', SaleController::class)->except('show');
        Route::resource('galleries', GalleryController::class)->except('show');
        Route::get('dealer-settings', [DealerSettingController::class, 'index'])->name('dealer-settings.index');
        Route::put('dealer-settings', [DealerSettingController::class, 'update'])->name('dealer-settings.update');
        Route::post('vehicles/{vehicle}/images', [VehicleImageController::class, 'store'])->name('vehicles.images.store');
        Route::put('vehicles/{vehicle}/images/{vehicleImage}', [VehicleImageController::class, 'update'])->name('vehicles.images.update');
        Route::delete('vehicles/{vehicle}/images/{vehicleImage}', [VehicleImageController::class, 'destroy'])->name('vehicles.images.destroy');
    });
});
