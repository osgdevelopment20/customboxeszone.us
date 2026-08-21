<x-mail::message>
# New Quote Received

## Customer Information
- **Name:** {{$quote->name}}
- **Email:** {{$quote->email}}
- **Phone:** {{$quote->phone}}
@if($quote->product_name)
- **Box Name:** {{$quote->product_name}}
@endif

<x-mail::table>
| Specification    | Details                |
|-----------------|:------------------------|
| Length          | {{$quote->length}}      |
| Width           | {{$quote->width}}       |
| Height          | {{$quote->height}}      |
| Quantity        | {{$quote->quantity}}    |
| Quantity 2      | {{$quote->quantity2}}   |
| Quantity 3      | {{$quote->quantity3}}   |
| Colors          | {{$quote->colors}}      |
| Box Style       | {{$quote->box_style}}   |
| Units           | {{$quote->units}}       |
| Stock           | {{$quote->stock}}       |
</x-mail::table>

@if($otherProducts)
## Additional Options
@foreach($otherProducts as $pro)
- {{$pro}}
@endforeach
@endif

@if($artDesign)
## Art Design Files
@foreach($artDesign as $art)
- [{{asset($art)}}]({{asset($art)}})
@endforeach
@endif

## Additional Details
<x-mail::table>
| Information     | Details                |
|-----------------|:------------------------|
| Quote Source    | [{{$quote->source}}]({{$quote->source}}) |
| IP Address      | {{$quote->ip_address}} |
| City            | {{$quote->city}}       |
| Region          | {{$quote->region}}     |
| Country         | {{$quote->country}}    |
| Location        | {{$quote->location}}   |
| Postal          | {{$quote->postal}}     |
</x-mail::table>

### Notes
{{$quote->notes}}

</x-mail::message>
