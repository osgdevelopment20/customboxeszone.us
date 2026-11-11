@php($invoice = $customer_invoice_mail)
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Invoice from {{ config('app.name') }}</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; color: #333; line-height: 1.5;">
    <h2>Invoice #{{ $invoice->id }}</h2>

    <p>Hello {{ optional($invoice->customer)->name ?? $invoice->name }},</p>
    <p>Thank you for your order. Your invoice details are below.</p>

    <table cellpadding="6" style="border-collapse: collapse; border: 1px solid #ddd;">
        <tr style="background: #f5f5f5;">
            <th align="left">Box</th>
            <th align="left">Size</th>
            <th align="right">Quantity</th>
            <th align="right">Unit Price</th>
        </tr>
        <tr>
            <td>{{ $invoice->box_name }}</td>
            <td>{{ $invoice->box_size }}</td>
            <td align="right">{{ $invoice->quantity }}</td>
            <td align="right">{{ $invoice->unit_price }}</td>
        </tr>
        @if($invoice->discount)
            <tr><td colspan="3" align="right">Discount</td><td align="right">{{ $invoice->discount }}</td></tr>
        @endif
        <tr><td colspan="3" align="right"><strong>Total</strong></td><td align="right"><strong>{{ $invoice->total_amount }}</strong></td></tr>
    </table>

    @if($invoice->shipping_address || $invoice->address)
        <h3>Shipping Address</h3>
        <p style="white-space: pre-line;">{{ $invoice->shipping_address ?: $invoice->address }}</p>
    @endif

    <p>If you have any questions, reply to this email or call {{ merchant_contact_phone() }}.</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>
