<x-mail::message>
# New Order Received

**Order Reference:** {{ $order->order_number }}
**Placed:** {{ $order->created_at->format('g:ia \o\n l jS F Y') }}

## Customer Information
- **Name:** {{ $order->name }}
- **Email:** {{ $order->email }}
- **Phone:** {{ $order->phone ?: 'Not provided' }}

## Items ({{ $order->items_count }})

@foreach($items as $index => $item)
### {{ $index + 1 }}. {{ $item->product_title ?: $item->product_name }}

<x-mail::table>
| Specification | Details |
|---------------|:--------|
| Category      | {{ $item->category ?: '-' }} |
| Dimensions    | {{ $item->dimensions ?: '-' }} |
| Quantity      | {{ $item->quantity }} |
| Product Page  | @if($item->product_slug) [View]({{ route('page', $item->product_slug) }}) @else - @endif |
</x-mail::table>

@if(!empty($item->artwork))
**Artwork Files:**
@foreach($item->artwork as $file)
- [{{ basename($file) }}]({{ asset('storage/' . $file) }})
@endforeach
@endif

@if($item->notes)
**Item Notes:** {{ $item->notes }}
@endif

@endforeach

@if($order->notes)
## Order Notes
{{ $order->notes }}
@endif

## Additional Details
<x-mail::table>
| Information | Details |
|-------------|:--------|
| Source      | {{ $order->source ?: '-' }} |
| IP Address  | {{ $order->ip_address ?: '-' }} |
| City        | {{ $order->city ?: '-' }} |
| Region      | {{ $order->region ?: '-' }} |
| Country     | {{ $order->country ?: '-' }} |
| Postal      | {{ $order->postal ?: '-' }} |
</x-mail::table>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
