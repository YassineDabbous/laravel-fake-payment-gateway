<?php

namespace YassineDabbous\FakePaymentGateway\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FakePaymentGatewayController extends Controller
{
    public function showPaymentPage(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'order_id' => 'required',
            'success_url' => 'required|url',
            'failure_url' => 'required|url',
            'webhook_url' => 'required|url',
            'payment_attempt_id' => 'required|string', // Added to fix usage with my app
        ]);

        // Use the namespaced view from our package
        return view('fake-payment-gateway::payment-page', $request->all());
    }

    public function processPayment(Request $request)
    {
        $status = $request->input('status');
        $orderId = $request->input('order_id');
        $successUrl = $request->input('success_url');
        $failureUrl = $request->input('failure_url');
        $webhookUrl = $request->input('webhook_url');

        // $transactionId = 'FPG_' . Str::random(20);
        $authoritativeTransactionId = $request->input('payment_attempt_id') ?? 'FPG_' . Str::random(20);

        // Simulate sending a webhook. The webhook url must be reachable from
        // the app running this controller; if the POST fails (e.g. a loopback
        // url that does not resolve here) still deliver the redirect below —
        // the payment-page UX must never depend on webhook delivery.
        try {
            Http::post($webhookUrl, [
                'order_id' => $orderId,
                'transaction_id' => $authoritativeTransactionId,
                'status' => $status,
                'amount' => $request->input('amount'),
                'paid_at' => now()->toDateTimeString(),
            ]);
        } catch (ConnectionException $e) {
            Log::warning('Fake payment gateway webhook dispatch failed', [
                'webhook_url' => $webhookUrl,
                'error' => $e->getMessage(),
            ]);
        }

        $returnUrl = ($status === 'success') ? $successUrl : $failureUrl;

        $paramsToAdd = ($status === 'success')
            ? ['transaction_id' => $authoritativeTransactionId, 'order_id' => $request->input('order_id')]
            : ['error' => 'payment_declined', 'order_id' => $request->input('order_id')];
            
        // Check if the base URL already has a query string.
        $separator = parse_url($returnUrl, PHP_URL_QUERY) ? '&' : '?';

        // Append the new parameters correctly.
        $finalRedirectUrl = $returnUrl . $separator . http_build_query($paramsToAdd);
        
        return redirect()->away($finalRedirectUrl);
    }
}