<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArchiveInvoiceController;
use App\Http\Controllers\CustomersReport;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicesAttachmentsController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\InvoicesReport;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
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
    Route::middleware(['auth', 'verified' ,'check.status', 'checkUserWorkHours'])->group(static function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        Route::resource('invoices', InvoiceController::class);
        Route::resource('sections', SectionController::class);
        Route::resource('products', ProductController::class);
        Route::get('section/{id}', [InvoiceController::class, 'getProducts']);
        Route::get('invoice_details/{id}', [InvoicesDetailsController::class, 'edit'])->name('invoice_details.edit');
        Route::get('/invoice_details/{invoiceNumber}/view-file/{attachmentId}', [InvoicesDetailsController::class, 'view_file'])->name('invoice_details.view-file');
        Route::get('/invoice_details/{invoiceNumber}/download-file/{file_name}', [InvoicesDetailsController::class, 'download_file'])->name('invoice_details.download-file');
        Route::post('delete_file', [InvoicesDetailsController::class, 'destroy'])->name('invoice_details.delete_file');
        Route::get('/show_status/{id}', [InvoiceController::class, 'show'])->name('status.show');

        Route::post('/update_satus/{id}', [InvoiceController::class, 'update_status'])->name('status.update');

        Route::resource('attachments', InvoicesAttachmentsController::class);

        Route::get('paid_invoices', [InvoiceController::class, 'paid_invoices'])->name('paid_invoices');
        Route::get('unpaid_invoices', [InvoiceController::class, 'unpaid_invoices'])->name('unpaid_invoices');
        Route::get('partial_paid_invoices', [InvoiceController::class, 'partial_paid_invoices'])->name('partial_paid_invoices');
        Route::delete('archive_invoice', [InvoiceController::class, 'archive_invoice'])->name('invoices.archive');

        Route::resource('archive', ArchiveInvoiceController::class);

        Route::get('print_invoice/{id}', [InvoiceController::class, 'print_invoice'])->name('print_invoice');

        Route::get('invoices_export', [InvoiceController::class, 'export'])->name('export_invoice');

        Route::resource('users' , UserController::class);
        Route::resource('roles' , RoleController::class);

        Route::get('invoices_report' , [InvoicesReport::class , 'index'])->name('invoices_report');
        Route::post('invoice_search_report' , [InvoicesReport::class , 'search_invoices'])->name('invoices_report_search');

        Route::get('customers_report' , [CustomersReport::class , 'index'])->name('customers_report');
        Route::post('customer_search_report' , [CustomersReport::class , 'search_customers'])->name('customers_report_search');

        Route::get('MarkAsRead_all',[InvoiceController::class , 'markAsRead_all'])->name('MarkAsRead_all');

    });
});
