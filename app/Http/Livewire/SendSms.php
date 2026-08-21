<?php

namespace App\Http\Livewire;

use App\Models\Campaign;
use App\Models\Contact;
use Illuminate\Http\Request;
use Livewire\Component;
use Twilio\Rest\Client;

class SendSms extends Component
{

    public $campaign;


    public $contacts;






    public function sendSms() {


        $campaign = Campaign::find($this->campaign );
        $recipients = Contact::whereIn('id', $this->contacts)->get();
        foreach ($recipients as $recipient) {
            $this->sendMessage($campaign->body, $recipient->phone);
        }
        return redirect()->route('dashboard.campaign.sms.campaign')->with('success', 'Campaign Sent Successfully');

    }

    private function sendMessage($message, $recipients)
    {
        $account_sid = config('services.twilio.sid');
        $auth_token = config('services.twilio.auth_token');
        $twilio_number = config('services.twilio.number');
        $client = new Client($account_sid, $auth_token);
        $client->messages->create($recipients,
            ['from' => $twilio_number, 'body' => $message] );
    }






    public function render()
    {
        $campaignData = Campaign::all();
        $contactsData = Contact::all();

        return view('livewire.send-sms',compact('campaignData','contactsData'));
    }
}
