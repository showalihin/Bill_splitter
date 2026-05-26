<?php

use App\Http\Controllers\BillSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Public Read-Only Bill Share
Route::get('/shared/{token}', [\App\Http\Controllers\SharedBillController::class, 'show'])->name('bills.shared');

Route::middleware(['auth', 'verified'])->group(function () {

    // -----------------------------------------------------------------
    // Admin Panel
    // -----------------------------------------------------------------
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
    });

    // -----------------------------------------------------------------
    // Profile
    // -----------------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // -----------------------------------------------------------------
    // Restaurants (standard CRUD)
    // -----------------------------------------------------------------
    Route::resource('restaurants', RestaurantController::class);

    // "Make Global" workflow
    Route::post('restaurants/{restaurant}/request-global', [RestaurantController::class, 'requestGlobal'])
         ->name('restaurants.request-global');

    Route::post('restaurants/{restaurant}/approve', [RestaurantController::class, 'approve'])
         ->name('restaurants.approve');

    Route::post('restaurants/{restaurant}/reject', [RestaurantController::class, 'reject'])
         ->name('restaurants.reject');

    // -----------------------------------------------------------------
    // Menu Items  (nested under restaurants)
    // /restaurants/{restaurant}/menu-items/create
    // /restaurants/{restaurant}/menu-items        [POST store]
    // /restaurants/{restaurant}/menu-items/{menuItem}/edit
    // /restaurants/{restaurant}/menu-items/{menuItem} [PUT update]
    // /restaurants/{restaurant}/menu-items/{menuItem} [DELETE destroy]
    // -----------------------------------------------------------------
    Route::prefix('restaurants/{restaurant}/menu-items')
         ->name('restaurants.menu-items.')
         ->group(function () {
             Route::get('create',              [MenuItemController::class, 'create'])->name('create');
             Route::post('/',                  [MenuItemController::class, 'store'])->name('store');
             Route::get('{menuItem}/edit',     [MenuItemController::class, 'edit'])->name('edit');
             Route::put('{menuItem}',          [MenuItemController::class, 'update'])->name('update');
             Route::delete('{menuItem}',       [MenuItemController::class, 'destroy'])->name('destroy');
         });

    // -----------------------------------------------------------------
    // Bill Splitting Sessions
    // -----------------------------------------------------------------
    Route::get('bills',                    [BillSessionController::class, 'index'])->name('bills.index');
    Route::get('bills/create',             [BillSessionController::class, 'create'])->name('bills.create');
    Route::post('bills',                   [BillSessionController::class, 'store'])->name('bills.store');
    Route::get('bills/{bill}',             [BillSessionController::class, 'show'])->name('bills.show');
    Route::put('bills/{bill}',             [BillSessionController::class, 'update'])->name('bills.update');
    Route::delete('bills/{bill}',          [BillSessionController::class, 'destroy'])->name('bills.destroy');
    Route::post('bills/{bill}/finalize',   [BillSessionController::class, 'toggleFinalize'])->name('bills.finalize');
    Route::post('bills/{bill}/menu',       [BillSessionController::class, 'addCustomMenuItem'])->name('bills.menu.add');
    Route::post('bills/{bill}/scan',       [\App\Http\Controllers\MenuScannerController::class, 'scan'])->name('bills.menu.scan');

    // Participants
    Route::post('bills/{bill}/participants',                          [\App\Http\Controllers\BillParticipantController::class, 'store'])->name('bills.participants.store');
    Route::put('bills/{bill}/participants/{participant}',             [\App\Http\Controllers\BillParticipantController::class, 'update'])->name('bills.participants.update');
    Route::delete('bills/{bill}/participants/{participant}',          [\App\Http\Controllers\BillParticipantController::class, 'destroy'])->name('bills.participants.destroy');

    // Items
    Route::post('bills/{bill}/items',                                [\App\Http\Controllers\BillItemController::class, 'store'])->name('bills.items.store');
    Route::post('bills/{bill}/items/menu',                           [\App\Http\Controllers\BillItemController::class, 'storeFromMenu'])->name('bills.items.storeFromMenu');
    Route::put('bills/{bill}/items/{item}/assign',                   [\App\Http\Controllers\BillItemController::class, 'assignParticipants'])->name('bills.items.assign');
    Route::delete('bills/{bill}/items/{item}',                       [\App\Http\Controllers\BillItemController::class, 'destroy'])->name('bills.items.destroy');
});

require __DIR__.'/auth.php';
