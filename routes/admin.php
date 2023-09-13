<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

 Route::prefix('dashboard/admin/')->group(static function () {
    Route::middleware(['auth', 'verified'])->group(static function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        Route::resource('invoices' , InvoiceController::class);
        Route::resource('sections' , SectionController::class);
    });
});
