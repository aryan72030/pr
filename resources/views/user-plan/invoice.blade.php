<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $subscription->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .invoice-header { text-align: center; margin-bottom: 30px; }
        .invoice-details { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        table td, table th { padding: 10px; text-align: left; border-bottom: 1px solid #eee; }
        .total { font-size: 20px; font-weight: bold; }
        .print-btn { margin: 20px 0; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <p>Invoice #{{ $subscription->id }}</p>
            <p>Date: {{ $subscription->created_at->format('d M Y') }}</p>
        </div>

        <div class="invoice-details">
            <h3>Bill To:</h3>
            <p>
                <strong>{{ $subscription->user->name }}</strong><br>
                {{ $subscription->user->email }}
            </p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Duration</th>
                    <th>Period</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $subscription->plan->name }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $subscription->duration)) }}</td>
                    <td>{{ $subscription->start_date->format('d M Y') }} - {{ $subscription->end_date->format('d M Y') }}</td>
                    <td>${{ number_format($subscription->amount, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                    <td class="total">${{ number_format($subscription->amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 30px;">
            <p><strong>Payment Method:</strong> {{ ucfirst($subscription->payment_method) }}</p>
            @if($subscription->transaction_id)
                <p><strong>Transaction ID:</strong> {{ $subscription->transaction_id }}</p>
            @endif
            <p><strong>Status:</strong> {{ ucfirst($subscription->status) }}</p>
        </div>

        <div class="print-btn">
            <button onclick="window.print()" class="btn btn-primary">Print Invoice</button>
        </div>
    </div>
</body>
</html>
