<?php

use App\Http\Controllers\ClientsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Redirect::to('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/clients', [ClientsController::class, 'index'])->name('clients');
    Route::post('/client/new', [ClientsController::class, 'store'])->name('client.new');
    Route::post('/client/{id}', [ClientsController::class, 'store'])->name('client.update');
    Route::delete('/client/{id}', [ClientsController::class, 'delete'])->name('client.delete');

    Route::get('/orders', [OrdersController::class, 'index'])->name('orders');
    Route::post('/order/new', [OrdersController::class, 'store'])->name('order.new');
    Route::post('/order/{id}', [OrdersController::class, 'store'])->name('order.update');
    Route::delete('/order/{id}', [OrdersController::class, 'delete'])->name('order.delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
