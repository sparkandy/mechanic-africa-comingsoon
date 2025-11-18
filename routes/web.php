<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\WaitlistController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ContactManagementController;
use App\Http\Controllers\Admin\PartnerManagementController;
use App\Http\Controllers\Admin\TechnicianManagementController;
use App\Http\Controllers\Admin\WaitlistManagementController;
use App\Http\Controllers\Admin\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Contact Form Submission
Route::post('/submit-contact', [ContactController::class, 'submit'])->name('contact.submit');

// Partner Application
Route::post('/submit-partner', [PartnerController::class, 'submit'])->name('partner.submit');

// Technician Application
Route::post('/submit-technician', [TechnicianController::class, 'submit'])->name('technician.submit');

// Waitlist Submission
Route::post('/submit-waitlist', [WaitlistController::class, 'submit'])->name('waitlist.submit');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Authentication
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    
    // Protected Admin Routes
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Contact Management
        Route::prefix('contacts')->group(function () {
            Route::get('/', [ContactManagementController::class, 'index'])->name('admin.contacts.index');
            Route::get('/{id}', [ContactManagementController::class, 'show'])->name('admin.contacts.show');
            Route::post('/{id}/status', [ContactManagementController::class, 'updateStatus'])->name('admin.contacts.status');
            Route::delete('/{id}', [ContactManagementController::class, 'destroy'])->name('admin.contacts.destroy');
        });
        
        // Partner Management
        Route::prefix('partners')->group(function () {
            Route::get('/', [PartnerManagementController::class, 'index'])->name('admin.partners.index');
            Route::get('/{id}', [PartnerManagementController::class, 'show'])->name('admin.partners.show');
            Route::post('/{id}/status', [PartnerManagementController::class, 'updateStatus'])->name('admin.partners.status');
            Route::delete('/{id}', [PartnerManagementController::class, 'destroy'])->name('admin.partners.destroy');
        });
        
        // Technician Management
        Route::prefix('technicians')->group(function () {
            Route::get('/', [TechnicianManagementController::class, 'index'])->name('admin.technicians.index');
            Route::get('/{id}', [TechnicianManagementController::class, 'show'])->name('admin.technicians.show');
            Route::post('/{id}/status', [TechnicianManagementController::class, 'updateStatus'])->name('admin.technicians.status');
            Route::delete('/{id}', [TechnicianManagementController::class, 'destroy'])->name('admin.technicians.destroy');
        });
        
        // Waitlist Management
        Route::prefix('waitlist')->group(function () {
            Route::get('/', [WaitlistManagementController::class, 'index'])->name('admin.waitlist.index');
            Route::delete('/{id}', [WaitlistManagementController::class, 'destroy'])->name('admin.waitlist.destroy');
            Route::get('/export', [WaitlistManagementController::class, 'export'])->name('admin.waitlist.export');
        });
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    });
});
