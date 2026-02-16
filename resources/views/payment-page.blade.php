<!-- resources/views/payment-page.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fake Payment Gateway</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-header bg-primary text-white">
            <h3>Fake Payment Gateway</h3>
            <p class="mb-0">For Testing Purposes Only</p>
        </div>
        <div class="card-body">
            <h5 class="card-title">Order Summary</h5>
            <p><strong>Order ID:</strong> {{ $order_id }}</p>
            <p><strong>Amount:</strong> ${{ number_format($amount, 2) }}</p>
            <hr>
            
            <p>Simulate a payment outcome:</p>
            
            <form action="{{ route(config('fake-gateway.route_name_prefix') . 'process') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order_id }}">
                <input type="hidden" name="amount" value="{{ $amount }}">
                <input type="hidden" name="success_url" value="{{ $success_url }}">
                <input type="hidden" name="failure_url" value="{{ $failure_url }}">
                <input type="hidden" name="webhook_url" value="{{ $webhook_url }}">

                <input type="hidden" name="payment_attempt_id" value="{{ $payment_attempt_id }}">

                <div class="d-grid gap-2">
                    <button type="submit" name="status" value="success" class="btn btn-success">
                        Simulate Successful Payment
                    </button>
                    <button type="submit" name="status" value="failure" class="btn btn-danger">
                        Simulate Failed Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>