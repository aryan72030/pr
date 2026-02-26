<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $subscription->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        td, th { padding: 10px; border-bottom: 1px solid #eee; }
        .total { font-size: 20px; font-weight: bold; }
        @media print { button { display: none; } }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h1>INVOICE #{{ $subscription->id }}</h1>
        <p>Date: {{ $subscription->created_at->format('d M Y') }}</p>
        
        <h3>Bill To: {{ $subscription->user->name }}</h3>
        <p>{{ $subscription->user->email }}</p>

        <table>
            <tr>
                <th>Plan</th>
                <th>Duration</th>
                <th>Period</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>{{ $subscription->plan->name }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $subscription->duration)) }}</td>
                <td>{{ $subscription->start_date->format('d M Y') }} - {{ $subscription->end_date->format('d M Y') }}</td>
                <td>${{ number_format($subscription->amount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                <td class="total">${{ number_format($subscription->amount, 2) }}</td>
            </tr>
        </table>

        <p><strong>Payment:</strong> {{ ucfirst($subscription->payment_method) }}</p>
        @if($subscription->transaction_id)
            <p><strong>Transaction:</strong> {{ $subscription->transaction_id }}</p>
        @endif

        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">Print</button>
    </div>
</body>
</html>
