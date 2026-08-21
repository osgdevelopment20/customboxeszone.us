<?php

namespace App\Http\Livewire;

use App\Models\VariationQuote;
use App\Mail\VariationQuoteMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class VariationQuoteForm extends Component
{
    public $name;
    public $email;
    public $phone;
    public $quantity;
    public $product_id;
    public $variation_id;

    protected $listeners = ['variationChanged'];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'quantity' => 'required|integer|min:1',
        'variation_id' => 'required|exists:product_variations,id',
    ];

    protected $messages = [
        'name.required' => 'Name is required',
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'phone.required' => 'Phone number is required',
        'quantity.required' => 'Quantity is required',
        'quantity.integer' => 'Quantity must be a number',
        'quantity.min' => 'Quantity must be at least 1',
        'variation_id.required' => 'Please select a product variation',
    ];

    public function mount($productId)
    {
        $this->product_id = $productId;
        $this->quantity = 1; // Set default quantity
    }

    public function variationChanged($variationId)
    {
        $this->variation_id = $variationId;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedName()
    {
        $this->validateOnly('name');
    }

    public function updatedEmail()
    {
        $this->validateOnly('email');
    }

    public function updatedPhone()
    {
        $this->validateOnly('phone');
    }

    public function updatedQuantity()
    {
        $this->validateOnly('quantity');
    }

    public function submit()
    {
        $validatedData = $this->validate();

        $variationQuote = VariationQuote::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'quantity' => $this->quantity,
            'product_id' => $this->product_id,
            'variation_id' => $this->variation_id,
            'referrer_page' => 'variation_quotes',
        ]);

        // Send email notification
        try {
            Mail::to('quote@cusotmboxeszone.com')->send(new VariationQuoteMail($variationQuote));
        } catch (\Exception $e) {
            // Log error but don't stop the process
            Log::error('Failed to send variation quote email: ' . $e->getMessage());
        }

        session()->flash('success', 'Quote request submitted successfully! We will contact you soon.');

        return redirect()->route('thankyou');
    }

    public function render()
    {
        return view('livewire.variation-quote-form');
    }
}
