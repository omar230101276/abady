<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AlbumController as AdminAlbumController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Admin\CollaborationController as AdminCollaborationController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

use App\Http\Controllers\ClientBookingController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/albums', [AlbumController::class, 'index'])->name('albums');
Route::get('/media', [MediaController::class, 'index'])->name('media');
Route::get('/collaborations', [CollaborationController::class, 'index'])->name('collaborations');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Session Booking Public Routes
Route::get('/book', [BookingController::class, 'index'])->name('bookings.index');
Route::post('/book', [BookingController::class, 'store'])->name('book.store');
Route::get('/book/verify/{token}', [BookingController::class, 'verify'])->name('bookings.verify');

// Client Booking Portal Lookup & Portal Management
Route::get('/bookings/lookup', [ClientBookingController::class, 'showLookupForm'])->name('bookings.lookup.form');
Route::post('/bookings/lookup', [ClientBookingController::class, 'lookup'])->name('bookings.lookup');
Route::get('/bookings/{reference}', [ClientBookingController::class, 'show'])->name('bookings.portal');
Route::put('/bookings/{reference}', [ClientBookingController::class, 'update'])->name('bookings.update');
Route::post('/bookings/{reference}/cancel', [ClientBookingController::class, 'cancel'])->name('bookings.cancel');

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

/*
|--------------------------------------------------------------------------
| Admin Protected Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard landing
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Profile Management
    Route::get('profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
    
    // Albums & Photos CRUD
    Route::resource('albums', AdminAlbumController::class);
    Route::post('albums/{album}/photos', [AdminAlbumController::class, 'uploadPhotos'])->name('albums.photos.upload');
    Route::delete('albums/{album}/photos/{photo}', [AdminAlbumController::class, 'deletePhoto'])->name('albums.photos.destroy');
    
    // Videos CRUD
    Route::resource('videos', AdminVideoController::class)->except(['show']);
    
    // Collaborations CRUD
    Route::resource('collaborations', AdminCollaborationController::class)->except(['show']);
    
    // Contact Messages
    Route::get('contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::post('contacts/{contact}/read', [AdminContactController::class, 'markAsRead'])->name('contacts.read');
    Route::post('contacts/{contact}/unread', [AdminContactController::class, 'markAsUnread'])->name('contacts.unread');
    Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

    // Session Bookings Management
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/create', [AdminBookingController::class, 'create'])->name('bookings.create');
    Route::post('bookings', [AdminBookingController::class, 'store'])->name('bookings.store');
    Route::get('bookings/calendar', [AdminBookingController::class, 'calendar'])->name('bookings.calendar');
    Route::get('bookings/{booking}/edit', [AdminBookingController::class, 'edit'])->name('bookings.edit');
    Route::put('bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');
    Route::patch('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::delete('bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

    // Blocked Dates Management
    Route::post('blocked-dates', [AdminBookingController::class, 'blockDate'])->name('bookings.block-date');
    Route::delete('blocked-dates/{blockedDate}', [AdminBookingController::class, 'unblockDate'])->name('bookings.unblock-date');

    // Admin Settings Management
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('time-slots', [AdminSettingController::class, 'storeTimeSlot'])->name('time-slots.store');
    Route::delete('time-slots/{timeSlot}', [AdminSettingController::class, 'destroyTimeSlot'])->name('time-slots.destroy');
});
