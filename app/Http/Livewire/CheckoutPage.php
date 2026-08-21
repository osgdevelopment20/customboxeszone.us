<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\DetectsVisitorLocation;
use App\Mail\OrderPlacedMail;
use App\Models\CartOrder;
use App\Models\CartOrderItem;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

/**
 * Final step - collect the contact details and turn the cart into an order.
 */
class CheckoutPage extends Component
{
    use DetectsVisitorLocation;

    public $name;
    public $email;
    public $phone;
    public $notes;

    protected $listeners = ['locationDetected'];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]{7,30})$/',
            'notes' => 'nullable|string|max:3000',
        ];
    }

    protected $messages = [
        'name.required' => 'Please tell us your name.',
        'name.min' => 'Name must be at least 3 characters.',
        'email.required' => 'Email is required so we can send your quote.',
        'email.email' => 'Please enter a valid email address.',
        'phone.required' => 'A phone number helps us confirm your order faster.',
        'phone.regex' => 'Please enter a valid phone number.',
        'notes.max' => 'Notes cannot exceed 3000 characters.',
    ];

    public function mount(CartService $cart)
    {
        // An empty cart is bounced back by the controller before we get here;
        // this only prefills what the customer already typed on the product page.
        $details = $cart->contactDetails();

        $this->name = $details['name'];
        $this->email = $details['email'];
        $this->phone = $details['phone'];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function placeOrder(CartService $cart)
    {
        $this->validate();

        if ($cart->isEmpty()) {
            session()->flash('error', 'Your cart is empty.');

            return redirect()->route('cart.quote');
        }

        $items = $cart->items();

        try {
            $order = DB::transaction(function () use ($items) {
                $order = CartOrder::create(array_merge([
                    'order_number' => CartOrder::generateOrderNumber(),
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'notes' => $this->notes,
                    'items_count' => count($items),
                    'total_quantity' => collect($items)->sum(fn ($item) => (int) $item['quantity']),
                    'status' => 'pending',
                    'source' => route('checkout.page'),
                ], $this->locationPayload()));

                foreach ($items as $item) {
                    CartOrderItem::create([
                        'cart_order_id' => $order->id,
                        'product_id' => $item['product_id'] ?? null,
                        'product_name' => $item['product_name'] ?? null,
                        'product_title' => $item['product_title'] ?? null,
                        'product_slug' => $item['product_slug'] ?? null,
                        'category' => $item['category'] ?? null,
                        'image' => $item['image'] ?? null,
                        'length' => $item['length'] ?? null,
                        'width' => $item['width'] ?? null,
                        'height' => $item['height'] ?? null,
                        'units' => $item['units'] ?? null,
                        'quantity' => (int) ($item['quantity'] ?? 1),
                        'artwork' => $item['artwork'] ?? [],
                        'notes' => $item['notes'] ?? null,
                    ]);
                }

                return $order;
            });
        } catch (\Exception $e) {
            Log::error('Order submission failed: ' . $e->getMessage());
            session()->flash('error', 'We could not submit your order. Please try again or call us directly.');

            return null;
        }

        try {
            Mail::to(config('app.to_mail'))->send(new OrderPlacedMail($order->load('items')));
        } catch (\Exception $e) {
            Log::error('Order email failed: ' . $e->getMessage());
        }

        $cart->clear();

        session()->flash('confirmation', [
            'heading' => 'Your order has been received',
            'reference' => $order->order_number,
            'message' => 'Thank you, ' . $order->name . '. Our packaging specialists are reviewing your requirements and will email your full quote within 24 hours.',
        ]);

        return redirect()->route('thankyou');
    }

    public function render(CartService $cart)
    {
        return view('livewire.checkout-page', [
            'items' => $cart->items(),
            'totalQuantity' => $cart->totalQuantity(),
        ]);
    }
}
