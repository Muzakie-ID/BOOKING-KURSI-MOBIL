<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::get('/', [BookingController::class, 'index'])->name('home');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', [BookingController::class, 'index'])->name('home');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Booking & Seat Selection Routes
Route::get('/check-booking', [App\Http\Controllers\SeatSelectionController::class, 'showSearchForm'])->name('booking.checkin');
Route::post('/check-booking', [App\Http\Controllers\SeatSelectionController::class, 'checkBooking'])->name('booking.verify');
Route::post('/booking/{code}/store-seats', [App\Http\Controllers\SeatSelectionController::class, 'storeSeats'])->name('booking.store_seats');
Route::get('/booking/{code}/ticket', [App\Http\Controllers\SeatSelectionController::class, 'showTicket'])->name('booking.ticket');

// Admin Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Protected Routes
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Fleet Management
    Route::resource('fleets', App\Http\Controllers\Admin\FleetController::class)->names('admin.fleets');
    
    // Route & Location Management
    Route::resource('routes', App\Http\Controllers\Admin\RouteController::class)->names('admin.routes');

    // Settings
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');

    // Schedule & Fleet Assignment
    Route::get('/schedules', [App\Http\Controllers\Admin\ScheduleManagementController::class, 'index'])->name('admin.schedules.index');
    Route::get('/schedules/pool/{date}', [App\Http\Controllers\Admin\ScheduleManagementController::class, 'show'])->name('admin.schedules.show');
    Route::post('/schedules/store', [App\Http\Controllers\Admin\ScheduleManagementController::class, 'store'])->name('admin.schedules.store');
    Route::post('/schedules/{schedule}/add-passengers', [App\Http\Controllers\Admin\ScheduleManagementController::class, 'addPassengers'])->name('admin.schedules.add_passengers');
});
