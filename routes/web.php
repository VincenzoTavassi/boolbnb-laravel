<?php

use App\Http\Controllers\Admin\ApartmentController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Guest\HomeController as GuestHomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// # Admin/Apartment Owner routes
Route::resource('apartments', ApartmentController::class)->middleware('auth');
// # Admin/Messages Owner routes
Route::resource('messages', MessageController::class)->middleware('auth')->only([
    'index'
]);;

// # Guest route
Route::get('/',    [GuestHomeController::class,    'homepage'])->name('homepage');

// # Protected routes
Route::middleware('auth')
    ->prefix('admin')   // * routes url start with "/admin." 
    ->name('admin.')    // * routes name start with "admin." 
    ->group(
        function () {
            // # Soft-delete and trash for projects
            Route::get('/apartments/trash', [ApartmentController::class, 'trash'])->name('apartments.trash');
            Route::put('/apartments/{apartment}/restore', [ApartmentController::class, 'restore'])->name('apartments.restore');
            Route::delete('/apartments/{apartment}/forcedelete', [ApartmentController::class, 'forcedelete'])->name('apartments.forcedelete');

            // # Personal routes for payments of sponsored apartments
            Route::get('apartments/payment/gateway/{apartment}', [PaymentController::class, 'gateway'])->name('payment.gateway');
            Route::get('apartments/payment/{apartment}', [PaymentController::class, 'show'])->name('payment.show');
            Route::post('apartments/payment/checkpay/{apartment}', [PaymentController::class, 'check_pay'])->name('payment.checkpay');




            // # Personal routes for messages
            Route::get('/messages/trash', [MessageController::class, 'trash'])->name('messages.trash');
            Route::get('/messages/{apartment}/', [MessageController::class, 'listByApartment'])->name('messages.listByApartment');
            Route::get('/messages/{apartment}/{message}', [MessageController::class, 'show'])->name('messages.show');
            Route::put('/messages/{apartment}/{message}/restore', [MessageController::class, 'restore'])->name('messages.restore');
            Route::delete('/messages/{apartment}/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
            Route::delete('/messages/{apartment}/{message}/forcedelete', [MessageController::class, 'forcedelete'])->name('messages.forcedelete');

            // # Apartments resource
            Route::get('/dashboard',   [AdminHomeController::class,    'dashboard'])->name('dashboard');
        }
    );

// ! Generated routes, do not touch
// # Protected profile's routes
Route::middleware('auth')
    ->prefix('profile')      // * routes url start with "/profile." 
    ->name('profile.')       // * routes name start with "profile." 
    ->group(
        function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        }
    );

require __DIR__ . '/auth.php';
