<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::prefix('customer')->group(function () {
    Route::post('/', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/{id}', [CustomerController::class, 'getBalance'])->name('customer.get_balance')->whereNumber('id');
    Route::put('/{id}/update-balance', [CustomerController::class, 'updateBalance'])->name('customer.update')->whereNumber('id');
});

Route::get('info', function () {
    phpinfo();
});

Route::get('log', function () {
    Log::warning('Testing Log');

    dd('logged');
});
