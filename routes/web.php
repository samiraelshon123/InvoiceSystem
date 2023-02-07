<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceArchieveController;
use App\Http\Controllers\InvoiceAttachmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SectionController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

// Auth::routes(['register' => false]);
Auth::routes();
// Route::get('/{page}', 'AdminController@index');
Route::get('/{page}', [AdminController::class, 'index']);



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('dashboard')->middleware('auth:web')->group(function() {
    Route::resource('invoices', InvoiceController::class);
    Route::resource('sections', SectionController::class);
    Route::resource('products', ProductController::class);
    Route::resource('invoiceDetails', InvoiceDetailsController::class);
    Route::resource('invoiceAttachment', InvoiceAttachmentController::class);
    Route::get('section/{id}', [InvoiceController::class, 'getproducts'])->name('section');
    Route::get('View_file/{invoice_number}/{file_name}', [InvoiceDetailsController::class, 'open_file'])->name('View_file');
    Route::get('download/{invoice_number}/{file_name}', [InvoiceDetailsController::class, 'get_file'])->name('download');
    Route::post('delete_file', [InvoiceDetailsController::class, 'destroy'])->name('delete_file');
    Route::post('/Status_Update/{id}', [InvoiceController::class, 'Status_Update'])->name('Status_Update');
    Route::get('invoice_paid', [InvoiceController::class, 'invoice_paid'])->name('invoice_paid');
    Route::get('invoice_unpaid', [InvoiceController::class, 'invoice_unpaid'])->name('invoice_unpaid');
    Route::get('invoice_partial', [InvoiceController::class, 'invoice_partial'])->name('invoice_partial');
    Route::resource('invoices_archive', InvoiceArchieveController::class);
    R