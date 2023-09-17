<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicesAttachmentsController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductController;
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

        Route::resource('invoices', InvoiceController::class);
        Route::resource('sections', SectionController::class);
        Route::resource('products', ProductController::class);
        Route::get('section/{id}', [InvoiceController::class, 'getProducts']);
        Route::get('invoice_details/{id}', [InvoicesDetailsController::class, 'edit'])->name('invoice_details.edit');
        Route::get('/invoice_details/{invoiceNumber}/view-file/{attachmentId}', [InvoicesDetailsController::class, 'view_file'])->name('invoice_details.view-file');
        Route::get('/invoice_details/{invoiceNumber}/download-file/{file_name}', [InvoicesDetailsController::class, 'download_file'])->name('invoice_details.download-file');
        Route::post('delete_file', [InvoicesDetailsController::class, 'destroy'])->name('invoice_details.delete_file');
        Route::resource('attachments', InvoicesAttachmentsController::class);
    });
});
