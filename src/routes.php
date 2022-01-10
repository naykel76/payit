<?php

use Illuminate\Support\Facades\Route;
use Naykel\Payit\Controllers\PaymentController;

Route::middleware(['web', 'auth'])->prefix('payments')->name('payments')->group(function () {

    Route::post('/pay', [PaymentController::class, 'pay'])->name('.pay');
    Route::get('/approval', [PaymentController::class, 'approval'])->name('.approval');
    Route::get('/cancelled', [PaymentController::class, 'cancelled'])->name('.cancelled');
    Route::get('/confirmed', [PaymentController::class, 'confirmed'])->name('.confirmed');

});


