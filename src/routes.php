<?php

use Illuminate\Support\Facades\Route;
use Naykel\Payit\Http\Controllers\InitiatePaymentController;
use Naykel\Payit\Http\Controllers\PaymentApprovalController;
use Naykel\Payit\Http\Controllers\PaymentCancelledController;
use Naykel\Payit\Http\Controllers\PaymentSuccessController;

Route::middleware(['web', 'auth'])->prefix('payment')->name('payment.')->group(function () {

    Route::post('/initiate', InitiatePaymentController::class)->name('initiate');
    Route::get('/approval', PaymentApprovalController::class)->name('approval');
    Route::get('/success', PaymentSuccessController::class)->name('success');
    Route::get('/cancelled', PaymentCancelledController::class)->name('cancelled');
});
