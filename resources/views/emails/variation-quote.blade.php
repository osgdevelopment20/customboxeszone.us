<x-mail::message>
# New Order Request Received

## Customer Information
- **Name:** {{ $variationQuote->name }}
- **Email:** {{ $variationQuote->email }}
- **Phone:** {{ $variationQuote->phone }}
- **Quantity:** {{ $variationQuote->quantity }}

## Product Details
- **Product Name:** {{ $product->title }}
- **Product URL:** [{{ config('app.url') }}/{{ $product->slug }}]({{ config('app.url') }}/{{ $product->slug }})

## Variation Details
@if($variation)
- **Variation Title:** {{ $variation->title }}

### Specifications
{!! $variation->specifications !!}
@endif

## Submission Details
- **Submitted At:** {{ $variationQuote->created_at->format('g:ia \o\n l jS F Y') }}
- **Referrer Page:** {{ $variationQuote->referrer_page }}

<x-mail::button :url="route('dashboard.orders.view', $variationQuote->id)">
View Order Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
