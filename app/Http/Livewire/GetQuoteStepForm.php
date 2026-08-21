<?php

namespace App\Http\Livewire;

use App\Jobs\SendQuoteEmailJob;
use App\Mail\QuoteMail;
use App\Models\Quote;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class GetQuoteStepForm extends Component
{


    public $name;
    public $email;
    public $phone;
    public $quantity;
    public $notes;
    public $source_page;
    public $ip_address;
    public $city;
    public $country;
    public $region;
    public $location;
    public $postal;

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
            'quantity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:3000',
        ];
    }

    protected $messages = [
        'name.min' => 'Name must be at least 3 characters',
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'phone.regex' => 'Please enter a valid phone number',
        'quantity.integer' => 'Quantity must be a whole number',
        'notes.max' => 'Notes cannot exceed 3000 characters'

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
            $quote->quantity = $this->quantity;
            $quote->source = $this->source_page;
            $quote->ip_address = $this->ip_address;
            $quote->city = $this->city;
            $quote->region = $this->region;
            $quote->country = $this->country;
            $quote->location = $this->location;
            $quote->postal = $this->postal;
            $quote->notes = $this->notes;
            if ($quote->save()) {
                info($quote->name ."after save");
                try {
                    Mail::to(config('app.to_mail'))->send(new QuoteMail( $quote ));
                } catch (\Exception $e) {
                    info($e);
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
        return view('livewire.get-quote-step-form');
    }
}
