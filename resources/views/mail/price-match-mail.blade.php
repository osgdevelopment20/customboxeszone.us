<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Price Match Request</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; color: #333; line-height: 1.5;">
    <h2>New Price Match Request</h2>

    <table cellpadding="6" style="border-collapse: collapse;">
        <tr><td><strong>Name</strong></td><td>{{ trim($price_match->name . ' ' . $price_match->l_name) }}</td></tr>
        <tr><td><strong>Email</strong></td><td>{{ $price_match->email }}</td></tr>
        <tr><td><strong>Phone</strong></td><td>{{ $price_match->phone }}</td></tr>
        <tr><td><strong>Company</strong></td><td>{{ $price_match->company }}</td></tr>
        <tr><td><strong>Country</strong></td><td>{{ $price_match->country }}</td></tr>
    </table>

    @if(!empty($info))
        <h3>Details</h3>
        <ul>
            @foreach($info as $item)
                <li>{{ is_scalar($item) ? $item : json_encode($item) }}</li>
            @endforeach
        </ul>
    @endif

    @if($price_match->image)
        <p><strong>Competitor quote image:</strong>
            <a href="{{ asset('frontend/pricematch/' . $price_match->image) }}">{{ asset('frontend/pricematch/' . $price_match->image) }}</a>
        </p>
    @endif

    <h3>Message</h3>
    <p style="white-space: pre-line;">{{ $price_match->message }}</p>
</body>
</html>
