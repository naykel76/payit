<?php

use Illuminate\Support\Facades\Route;
use Naykel\Payit\Http\Controllers\InitiatePaymentController;
use Naykel\Payit\Http\Controllers\PaymentController;

Route::middleware(['web', 'auth'])->prefix('payment')->name('payment.')->group(function () {

    Route::post('/initiate', InitiatePaymentController::class)->name('initiate');
    // Route::get('/approval', HandlePaymentApprovalController::class)->name('approval');
    // Route::get('/cancel', HandlePaymentCancellationController::class)->name('cancel');
    // Route::get('/confirm', ConfirmPaymentController::class)->name('confirm');

    Route::get('/approval', [PaymentController::class, 'approval'])->name('approval');
    Route::get('/cancelled', [PaymentController::class, 'cancelled'])->name('cancelled');
    Route::get('/confirmed', [PaymentController::class, 'confirmed'])->name('confirmed');

});
