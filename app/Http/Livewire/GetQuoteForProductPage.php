<?php

namespace App\Http\Livewire;

use App\Jobs\SendQuoteEmailJob;
use App\Mail\QuoteMail;
use App\Models\Quote;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class GetQuoteForProductPage extends Component
{

    use WithFileUploads;

    public $name;
    public $email;
    public $phone;
    public $length;
    public $width;
    public $height;
    public $quantity;
    public $units;
    public $quantity2;
    public $notes;
    public $source_page;
    public $art_design = []; // Change to array
    public $ip_address;
    public $city;
    public $country;
    public $region;
    public $location;
    public $postal;

    protected $listeners = ['locationDetected'];

    public function locationDetected($data)
    {
        $this->ip_address = $data['ip'] ?? null;
        $this->city = $data['city'] ?? null;
        $this->region = $data['region'] ?? null;
        $this->country = $data['country'] ?? null;
        $this->location = $data['location'] ?? null;
        $this->postal = $data['postal'] ?? null;
    }


    protected function rules()
    {
        return [
            'name' => 'nullable|string|min:3',
            'email' => 'required|email',
            'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/',
            'length.numeric' => 'Length must be a number',
            'width.numeric' => 'Width must be a number',
            'height.numeric' => 'Height must be a number',
            'quantity' => 'nullable|integer|min:1',
            'quantity2' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:3000',
            'art_design' => 'nullable|array|max:10',
            'art_design.*' => [
                'file',
                'mimes:jpeg,png,jpg,gif,pdf,ai,psd,cdr,webp,svg',
                'mimetypes:' . implode(',', [
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                    'image/webp',
                    'application/pdf',
                    'application/illustrator',
                    'image/vnd.adobe.photoshop',
                    'application/x-photoshop',
                    'application/cdr',
                    'application/coreldraw',
                    'image/svg+xml'
                ])
            ],
        ];
    }

    protected $messages = [
        'name.min' => 'Name must be at least 3 characters',
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'phone.regex' => 'Please enter a valid phone number',
        'quantity.integer' => 'Quantity must be a whole number',
        'quantity2.integer' => 'Quantity must be a whole number',
        'notes.max' => 'Notes cannot exceed 3000 characters',
        'art_design.max' => 'You cannot upload more than 10 files',
        'art_design.*.file' => 'Each uploaded file must be valid',
        'art_design.*.mimes' => 'Invalid file type. Allowed types: JPEG, PNG, JPG, GIF, PDF, AI, PSD, CDR, WEBP, SVG',

    ];

    public function updated($propertyName)
    {

        $this->validateOnly($propertyName);
    }

    public function mount($source_page = null)
    {
        $this->source_page = $source_page ?? request()->fullUrl();
    }

    public function saveQuote()
    {
        $this->validate();
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
            $quote->units = $this->units;
            $quote->source = $this->source_page;
            $quote->ip_address = $this->ip_address;
            $quote->city = $this->city;
            $quote->region = $this->region;
            $quote->country = $this->country;
            $quote->location = $this->location;
            $quote->postal = $this->postal;
            $quote->notes = $this->notes;

            // Handle multiple file uploads
            if (!empty($this->art_design)) {
                $artworkPaths = [];
                foreach ($this->art_design as $file) {
                    try {
                        $fileName = rand(99999, 9999999);
                        $extension = $file->getClientOriginalExtension();
                        $filePath = $file->storeAs('artwork', $fileName . '.' . $extension, 'public');
                        $artworkPaths[] = $filePath;
                    } catch (\Exception $e) {
                        Log::error('File upload failed: ' . $e->getMessage());
                        continue;
                    }
                }
                $quote->art_design = json_encode($artworkPaths);
            }

            if ($quote->save()) {
                try {
                    Mail::to(config('app.to_mail'))->send(new QuoteMail( $quote ));
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
        return view('livewire.get-quote-for-product-page');
    }
}
