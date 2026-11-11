<x-mail::message>
# New Order Request

## Customer Information
- **Name:** {{$order->name}}
- **Email:** {{$order->email}}
- **Phone:** {{$order->phone}}
@if($order->product_name)
- **Box Name:** {{$order->product_name}}
@endif

<x-mail::table>
| Specification    | Details                |
|-----------------|:------------------------|
| Length          | {{$order->length}}      |
| Width           | {{$order->width}}       |
| Depth           | {{$order->depth}}       |
| Quantity        | {{$order->quantity}}    |
| Quantity 2      | {{$order->quantity2}}   |
| Quantity 3      | {{$order->quantity3}}   |
| Colors          | {{$order->colors}}      |
| Box Style       | {{$order->box_style}}   |
| Units           | {{$order->units}}       |
| Stock           | {{$order->stock}}       |
</x-mail::table>

@if($otherProducts)
## Additional Options
@foreach($otherProducts as $pro)
- {{$pro}}
@endforeach
@endif

@if($order->art_desgin)
## Art Design File
- [{{asset('frontend/banner/' . $order->art_desgin)}}]({{asset('frontend/banner/' . $order->art_desgin)}})
@endif

### Notes
{{$order->notes}}

</x-mail::message>
