<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        h1 {
            margin: 0;
            font-size: 24px;
        }

        .invoice-details {
            background-color: #f2f2f2;
            padding: 10px;
            border-radius: 5px;
        }

        .customer-info {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Invoice {{ $order->reference }}</h1>
        <div class="invoice-details">
            <p><strong>Fecha:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
            <p><strong>Estado:</strong> {{ $order->status->name }}</p>
        </div>
    </div>

    <div class="customer-info">
        <p><strong>Cliente:</strong> {{ $order->cart->user->first_name }} {{ $order->cart->user->last_name }}</p>
        <p><strong>Email:</strong> {{ $order->cart->user->email }}</p>
        <p><strong>Total:</strong> {{ $order->total_price }} USD</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->cart->products as $item)
                <tr>
                    <td>
                        <strong><span> {{ $item->variation->sku }} </span></strong> {{ $item->product->name }} 
                        <!-- Show Attributes of variation -->
                        @foreach ($item->variation->attributes as $attribute)
                            <span>- {{ $attribute->name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->unit_price }} USD</td>
                    <td>{{ $item->quantity * $item->unit_price }} USD</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p class="total">Total: {{ $order->total_price }} USD</p>
</body>
</html>