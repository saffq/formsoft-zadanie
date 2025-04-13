<?php


use App\Http\Controllers\Api\InvoiceController\InvoiceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('invoices.index');
})->name('home');

Route::resource('invoices', InvoiceController::class);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
