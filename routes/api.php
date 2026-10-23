<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Rutas publicas
Route::post('login', [AuthController::class, 'login'])->name('auth.login');

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {

    // Rutas para autenticacion
    Route::get('me', [AuthController::class, 'me'])->name('auth.me');
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Rutas para media
    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::get('media/{media}', [MediaController::class, 'show'])->name('media.show');
    Route::put('media/{media}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

    Route::get('media/{media}/availability', [MediaController::class, 'checkAvailability'])->name('media.availability');

    // Rutas para customers
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Rutas para bookings
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::delete('bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Rutas exclusivas para administrador
    Route::middleware('admin')->prefix('admin')->group(function () {

        // Rutas para users
        Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('users/{user}', [UserController::class, 'show'])->name('admin.users.show');
        Route::put('users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

});