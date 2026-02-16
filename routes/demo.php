<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::prefix(config('fake-gateway.prefix', 'fake'))
    ->name(config('fake-gateway.route_name_prefix', 'fake-gateway.'))
    ->middleware('web')
    ->group(function () {
        
        // The route that starts the payment process
        Route::get('/checkout', function() {
            $routeNamePrefix = config('fake-gateway.route_name_prefix');
            
            $queryParams = http_build_query([
                'order_id'    => 'ORD' . time(),
                'amount'      => rand(10, 500) . '.' . rand(10, 99),
                'success_url' => route($routeNamePrefix.'demo.success'),
                'failure_url' => route($routeNamePrefix.'demo.failure'),
                'webhook_url' => route($routeNamePrefix.'demo.webhook'),
            ]);

            return redirect()->away(route($routeNamePrefix . 'pay') . '?' . $queryParams);
        })->name('demo.checkout');

        // Demo Callback and Webhook routes
        Route::get('/demo/success', function (Request $request) {
            return "DEMO SUCCESS! Transaction ID: " . e($request->transaction_id) . "<br>Check your log file for the webhook data.";
        })->name('demo.success');

        Route::get('/demo/failure', function (Request $request) {
            return "DEMO FAILURE! Reason: " . e($request->error);
        })->name('demo.failure');

        Route::post('/demo/webhook', function (Request $request) {
            Log::info('DEMO WEBHOOK RECEIVED:', $request->all());
            return response()->json(['status' => 'demo webhook received']);
        })->name('demo.webhook')->withoutMiddleware([VerifyCsrfToken::class]);
    });