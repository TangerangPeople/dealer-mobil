<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DealerSettingController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\PurchaseApplicationController as AdminPurchaseApplicationController;
use App\Http\Controllers\Admin\PurchaseApplicationDocumentController as AdminPurchaseApplicationDocumentController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\ServiceBookingController;
use App\Http\Controllers\Admin\ServicePackageController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\VehicleImageController;
use App\Http\Controllers\Customer\Auth\CustomerSessionController;
use App\Http\Controllers\Customer\CustomerAccountController;
use App\Http\Controllers\Customer\PurchaseApplicationController as CustomerPurchaseApplicationController;
use App\Http\Controllers\Customer\PurchaseApplicationDocumentController as CustomerPurchaseApplicationDocumentController;
use App\Http\Controllers\Public\PublicPageController;
use App\Http\Controllers\Public\PurchaseApplicationController as PublicPurchaseApplicationController;
use App\Http\Controllers\Public\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/tentang', [PublicPageController::class, 'about'])->name('about');
Route::get('/mobil', [PublicPageController::class, 'vehiclesIndex'])->name('vehicles.index');
Route::get('/mobil/{vehicle}', [PublicPageController::class, 'vehicleShow'])->name('vehicles.show');
Route::get('/promo', [PublicPageController::class, 'promotionsIndex'])->name('promotions.index');
Route::get('/promo/{promotion}', [PublicPageController::class, 'promotionShow'])->name('promotions.show');
Route::get('/galeri', [PublicPageController::class, 'galleryIndex'])->name('gallery.index');
Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');
Route::get('/booking-service', [ServiceController::class, 'booking'])->name('booking-service');
Route::post('/booking-service', [ServiceController::class, 'store'])->name('booking-service.store');
Route::get('/kontak', [PublicPageController::class, 'contact'])->name('contact');
Route::get('/simulasi-kredit', [PublicPageController::class, 'creditSimulation'])->name('credit-simulation');
Route::get('/chat-sales/{sale?}', [PublicPageController::class, 'chatSales'])->whereNumber('sale')->name('sales.chat');

Route::get('/daftar', [CustomerSessionController::class, 'register'])->name('customer.register');
Route::post('/daftar', [CustomerSessionController::class, 'storeRegistration'])
    ->middleware('throttle:customer-register')
    ->name('customer.register.store');
Route::get('/masuk', [CustomerSessionController::class, 'create'])->name('customer.login');
Route::post('/masuk', [CustomerSessionController::class, 'storeLogin'])
    ->middleware('throttle:customer-login')
    ->name('customer.login.store');

Route::middleware('customer.authenticated')->group(function (): void {
    Route::get('/akun', [CustomerAccountController::class, 'index'])->name('customer.account');
    Route::post('/keluar', [CustomerSessionController::class, 'destroy'])->name('customer.logout');
    Route::get('/pengajuan-pembelian', [CustomerPurchaseApplicationController::class, 'index'])->name('purchase.index');
    Route::get('/pengajuan-pembelian/{vehicle:slug}', [PublicPurchaseApplicationController::class, 'create'])->name('purchase.create');
    Route::post('/pengajuan-pembelian/{vehicle:slug}', [PublicPurchaseApplicationController::class, 'store'])->name('purchase.store');
    Route::get('/akun/pengajuan-pembelian/{application}', [CustomerPurchaseApplicationController::class, 'show'])->name('purchase.show');
    Route::get('/akun/pengajuan-pembelian/{application}/dokumen/{document}', [CustomerPurchaseApplicationDocumentController::class, 'download'])->name('purchase.documents.download');
    Route::post('/akun/pengajuan-pembelian/{application}/whatsapp', [PublicPurchaseApplicationController::class, 'whatsapp'])->name('purchase.whatsapp');
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
        Route::resource('promotions', PromotionController::class)->except('show');
        Route::resource('sales', SaleController::class)->except('show');
        Route::resource('service-packages', ServicePackageController::class)->except('show');
        Route::resource('service-bookings', ServiceBookingController::class)->only(['index', 'update', 'destroy']);
        Route::get('purchase-applications', [AdminPurchaseApplicationController::class, 'index'])->name('purchase-applications.index');
        Route::get('purchase-applications/{application}', [AdminPurchaseApplicationController::class, 'show'])->name('purchase-applications.show');
        Route::put('purchase-applications/{application}/status', [AdminPurchaseApplicationController::class, 'updateStatus'])->name('purchase-applications.status');
        Route::get('purchase-applications/{application}/documents/{document}', [AdminPurchaseApplicationDocumentController::class, 'download'])->name('purchase-applications.documents.download');
        Route::resource('galleries', GalleryController::class)->except('show');
        Route::get('dealer-settings', [DealerSettingController::class, 'index'])->name('dealer-settings.index');
        Route::put('dealer-settings', [DealerSettingController::class, 'update'])->name('dealer-settings.update');
        Route::post('vehicles/{vehicle}/images', [VehicleImageController::class, 'store'])->name('vehicles.images.store');
        Route::put('vehicles/{vehicle}/images/{vehicleImage}', [VehicleImageController::class, 'update'])->name('vehicles.images.update');
        Route::delete('vehicles/{vehicle}/images/{vehicleImage}', [VehicleImageController::class, 'destroy'])->name('vehicles.images.destroy');
    });
});
