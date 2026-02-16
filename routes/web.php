<?php

use YassineDabbous\FakePaymentGateway\Http\Controllers\FakePaymentGatewayController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('fake-gateway.prefix', 'fake'))
    ->name(config('fake-gateway.route_name_prefix', 'fake-gateway.'))
    ->group(function () {
        // The page where the user "enters" their card details
        Route::get('/pay', [FakePaymentGatewayController::class, 'showPaymentPage'])->name('pay');

        // The endpoint that "processes" the payment
        Route::post('/process', [FakePaymentGatewayController::class, 'processPayment'])->name('process');
    });