<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\DetectsVisitorLocation;
use App\Mail\QuoteMail;
use App\Models\Product;
use App\Models\Quote;
use App\Services\CartService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Product page enquiry box.
 *
 * The same specification drives both calls to action: "Get Instant Quote"
 * submits a single quote straight away, "Add to Cart" parks the configuration
 * so several boxes can be ordered together at checkout.
 */
class ProductOrderForm extends Component
{
    use WithFileUploads, DetectsVisitorLocation;

    public $productId;
    public $productName;

    public $name;
    public $email;
    public $phone;
    public $length;
    public $width;
    public $height;
    public $units = 'inches';
    public $quantity = 100;
    public $artwork = [];

    public $sourcePage;

    protected $listeners = ['locationDetected'];

    protected function rules(): array
    {
        return [
            'name' => 'nullable|string|min:3|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|max:30',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'units' => 'nullable|in:inches,cm,mm',
            'quantity' => 'required|integer|min:1|max:10000000',
            'artwork' => 'nullable|array|max:10',
            'artwork.*' => 'file|max:20480|mimes:jpeg,png,jpg,gif,pdf,ai,psd,cdr,webp,svg',
        ];
    }

    protected $messages = [
        'name.min' => 'Name must be at least 3 characters.',
        'email.required' => 'Email is required so we can send your quote.',
        'email.email' => 'Please enter a valid email address.',
        'phone.regex' => 'Please enter a valid phone number.',
        'length.numeric' => 'Length must be a number.',
        'width.numeric' => 'Width must be a number.',
        'height.numeric' => 'Height must be a number.',
        'quantity.required' => 'Quantity is required.',
        'quantity.integer' => 'Quantity must be a whole number.',
        'quantity.min' => 'Quantity must be at least 1.',
        'artwork.max' => 'You cannot upload more than 10 files.',
        'artwork.*.max' => 'Each file must be 20MB or smaller.',
        'artwork.*.mimes' => 'Allowed file types: JPG, PNG, GIF, WEBP, SVG, PDF, AI, PSD, CDR.',
    ];

    public function mount($productId = null, $productName = null, $sourcePage = null)
    {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->sourcePage = $sourcePage ?? request()->fullUrl();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Submit the configuration as a one-off quote request.
     */
    public function getInstantQuote(CartService $cart)
    {
        $this->validate(array_merge($this->rules(), [
            'email' => 'required|email|max:255',
        ]));

        try {
            $quote = new Quote();
            $quote->name = $this->name;
            $quote->email = $this->email;
            $quote->phone = $this->phone;
            $quote->length = $this->length;
            $quote->width = $this->width;
            $quote->height = $this->height;
            $quote->units = $this->units;
            $quote->quantity = $this->quantity;
            $quote->product_id = $this->productId;
            $quote->product_name = $this->productName;
            $quote->source = $this->sourcePage;
            $quote->referrer_page = 'product_page';
            $quote->ip_address = $this->ip_address;
            $quote->city = $this->city;
            $quote->region = $this->region;
            $quote->country = $this->country;
            $quote->postal = $this->postal;

            $artwork = $this->storeArtwork();
            if (! empty($artwork)) {
                $quote->art_design = json_encode($artwork);
            }

            $quote->save();

            try {
                Mail::to(config('app.to_mail'))->send(new QuoteMail($quote));
            } catch (\Exception $e) {
                Log::error('Quote email failed: ' . $e->getMessage());
            }

            $this->resetForm();

            session()->flash('confirmation', [
                'heading' => 'Your quote request is on its way',
                'message' => 'Thank you for reaching out. One of our packaging specialists will review your requirements and get back to you with a tailored quote within 24 hours.',
            ]);

            return redirect()->route('thankyou');
        } catch (\Exception $e) {
            Log::error('Instant quote submission failed: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong while submitting your quote. Please try again.');

            return null;
        }
    }

    /**
     * Park the configuration in the cart and send the customer to it.
     */
    public function addToCart(CartService $cart)
    {
        $this->validate();

        $product = Product::with('category')->find($this->productId);

        if (! $product) {
            session()->flash('error', 'This product is no longer available.');

            return null;
        }

        $cart->add($product, [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'units' => $this->units,
            'quantity' => $this->quantity,
            'artwork' => $this->storeArtwork(),
        ]);

        session()->flash('cart_success', ($product->product_title ?: $product->product_name) . ' has been added to your cart.');

        return redirect()->route('cart.quote');
    }

    /**
     * Move the temporary Livewire uploads into permanent storage.
     *
     * @return array<int, string> Relative paths on the public disk.
     */
    protected function storeArtwork(): array
    {
        $paths = [];

        foreach ($this->artwork as $file) {
            try {
                $fileName = uniqid('artwork_', true) . '.' . $file->getClientOriginalExtension();
                $paths[] = $file->storeAs('artwork', $fileName, 'public');
            } catch (\Exception $e) {
                Log::error('Artwork upload failed: ' . $e->getMessage());
            }
        }

        return array_values(array_filter($paths));
    }

    protected function resetForm(): void
    {
        $this->reset(['name', 'email', 'phone', 'length', 'width', 'height', 'artwork']);
        $this->quantity = 100;
        $this->units = 'inches';
    }

    public function render()
    {
        return view('livewire.product-order-form');
    }
}
