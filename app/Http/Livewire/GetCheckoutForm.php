<?php

namespace App\Http\Livewire;

use App\Mail\QuoteMail;
use App\Models\Product;
use App\Models\Quote;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Livewire\Concerns\ThrottlesSubmissions;
use Livewire\Component;
use Livewire\WithFileUploads;

class GetCheckoutForm extends Component
{
    use WithFileUploads, ThrottlesSubmissions;

    public $name;
    public $email;
    public $phone;
    public $length;
    public $width;
    public $height;
    public $quantity;
    public $quantity2;
    public $quantity3;
    public $colors;
    public $product_name;
    public $box_style;
    public $units;
    public $stock;
    public $other_products = [];
    public $art_design = [];
    public $notes;
    public $source;
    public $source_page;
    public $ip_address;
    public $city;
    public $country;
    public $region;
    public $location;
    public $postal;
    public $item_id;
    public $product; // loaded product for left-side details

    protected $listeners = ['locationDetected'];

    public function locationDetected($data)
    {
        $this->ip_address = $data['ip'];
        $this->city = $data['city'];
        $this->region = $data['region'];
        $this->country = $data['country'];
        $this->location = $data['location'];
        $this->postal = $data['postal'];
    }

    protected function rules()
    {
        return [
            'name' => 'nullable|string|min:3',
            'email' => 'required|email',
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/',
            'length' => 'nullable|numeric|regex:/^\d*\.?\d*$/',
            'width' => 'nullable|numeric|regex:/^\d*\.?\d*$/',
            'height' => 'nullable|numeric|regex:/^\d*\.?\d*$/',
            'quantity' => 'nullable|integer|min:1',
            'quantity2' => 'nullable|integer|min:1',
            'quantity3' => 'nullable|integer|min:1',
            'colors' => 'nullable|string',
            'product_name' => 'nullable|string',
            'box_style' => 'nullable|string',
            'units' => 'nullable|string',
            'stock' => 'nullable|string',
            'other_products' => 'nullable|array',
            'other_products.*' => 'string',
            'art_design' => 'nullable|array|max:10',
            'art_design.*' => 'file|mimes:jpeg,png,jpg,gif,pdf,ai,psd,cdr,webp,svg',
            'notes' => 'nullable|string|max:3000',
        ];
    }

    protected $messages = [
        'name.min' => 'Name must be at least 3 characters',
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'phone.regex' => 'Please enter a valid phone number',
        'art_design.max' => 'You cannot upload more than 10 files',
        'art_design.*.mimes' => 'Invalid file type. Allowed types: JPEG, PNG, JPG, GIF, PDF, AI, PSD, CDR, WEBP, SVG',
        'notes.max' => 'Notes cannot exceed 3000 characters'
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount($source_page = null, $item_id = null)
    {
        $this->source_page = $source_page ?? request()->fullUrl();
        $this->item_id = $item_id ?? request()->query('item_id');

        if ($this->item_id) {
            $this->product = Product::find($this->item_id);
            if ($this->product) {
                $this->product_name = $this->product->product_title ?? $this->product->product_name ?? null;
            }
        }
    }

    public function saveQuote()
    {
        $this->validate();
        $this->throttleSubmission();
        try {
            $quote = new Quote();
            $quote->name = $this->name;
            $quote->email = $this->email;
            $quote->phone = $this->phone;
            $quote->length = $this->length;
            $quote->width = $this->width;
            $quote->height = $this->height;
            $quote->quantity = $this->quantity;
            $quote->quantity2 = $this->quantity2;
            $quote->quantity3 = $this->quantity3;
            $quote->colors = $this->colors;
            $quote->product_name = $this->product_name;
            $quote->box_style = $this->box_style;
            $quote->stock = $this->stock;
            $quote->units = $this->units;
            $quote->source = $this->source_page;
            $quote->ip_address = $this->ip_address;
            $quote->city = $this->city;
            $quote->region = $this->region;
            $quote->country = $this->country;
            $quote->location = $this->location;
            $quote->postal = $this->postal;

            if (!empty($this->other_products)) {
                $quote->other_products = json_encode($this->other_products);
            }

            if (!empty($this->art_design)) {
                $artworkPaths = [];
                foreach ($this->art_design as $file) {
                    try {
                        $filePath = store_artwork_upload($file);
                        if ($filePath) {
                            $artworkPaths[] = $filePath;
                        }
                    } catch (\Exception $e) {
                        Log::error('File upload failed: ' . $e->getMessage());
                        continue;
                    }
                }
                $quote->art_design = json_encode($artworkPaths);
            }

            if ($this->item_id) {
                $this->notes = trim(($this->notes ?? '') . "\nProduct ID: " . $this->item_id);
            }
            $quote->notes = $this->notes;

            if ($quote->save()) {
                try {
                    Mail::to(config('app.to_mail'))->send(new QuoteMail($quote));
                } catch (\Exception $e) {
                    Log::error('Quote email failed: ' . $e->getMessage());
                }
                $this->reset();
                session()->flash('message', 'Your quote request has been submitted successfully. Our representative will contact you soon.');
                return redirect()->route('thankyou');
            }
        } catch (\Exception $e) {
            Log::error('Quote submission failed: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong. Please try again.');
            return null;
        }
    }

    public function render()
    {
        return view('livewire.get-checkout-form');
    }
}


