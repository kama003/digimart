<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3b82f6;
        }
        .header h1 {
            margin: 0;
            color: #3b82f6;
            font-size: 28px;
        }
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .invoice-info-left, .invoice-info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .invoice-info-right {
            text-align: right;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #374151;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            font-weight: bold;
            font-size: 16px;
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <p style="margin: 5px 0; color: #666;">{{ config('app.name', 'Digital Marketplace') }}</p>
    </div>

    <div class="invoice-info">
        <div class="invoice-info-left">
            <p><span class="info-label">Bill To:</span></p>
            <p>
                {{ $transaction->user->name }}<br>
                {{ $transaction->user->email }}
            </p>
        </div>
        <div class="invoice-info-right">
            <p><span class="info-label">Invoice #:</span> {{ $transaction->id }}</p>
            <p><span class="info-label">Date:</span> {{ $transaction->created_at->format('F j, Y') }}</p>
            <p><span class="info-label">Payment Method:</span> {{ ucfirst($transaction->payment_gateway) }}</p>
            <p><span class="info-label">Status:</span> <span class="badge badge-success">{{ ucfirst($transaction->status) }}</span></p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Seller</th>
                <th class="text-right">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaction->transactionItems as $item)
                <tr>
                    <td>{{ $item->product->title }}</td>
                    <td>{{ $item->product->category->name }}</td>
                    <td>{{ $item->seller->name }}</td>
                    <td class="text-right">${{ number_format($item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">Total Amount:</td>
                <td class="text-right">${{ number_format($transaction->amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Thank you for your purchase!</p>
        <p>This is a computer-generated invoice and does not require a signature.</p>
        <p>{{ config('app.name', 'Digital Marketplace') }} &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
