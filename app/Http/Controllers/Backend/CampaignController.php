<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\CampaignDataTable;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Intervention\Image\Facades\Image;
use Twilio\TwiML\MessagingResponse;

class CampaignController extends Controller
{
    public function index(CampaignDataTable $dataTable)
    {
        return $dataTable->render('backend.pages.marketing.campaign.index');
    }


    public function create() {
        return view('backend.pages.marketing.campaign.create');
    }

    public function store(Request $request) {

        $request->validate([
            'name' => 'required|min:2|max:255',
            'body' => 'required|min:2|max:255',
        ]);
        $campaign = new Campaign();
        $campaign->name = $request->name;
        $campaign->type = $request->type;
        $campaign->body = $request->body;
            if ($request->hasFile('mms'))
            {
                $image = $request->file('mms');
                $extension = $image->getClientOriginalExtension();
                $filename = rand(111, 99999) . '.' .  $extension;
                $image_path = 'campaign/' . $filename;
                Image::make($image)->save($image_path);
                $campaign->mms =$filename;
            }
        $campaign->save();

        return redirect()->route('dashboard.campaign.index')->with('success', 'Campaign Created Successfully');

    }

    public function edit(Campaign $campaign) {
        return view('backend.pages.marketing.campaign.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign) {
        $request->validate([
            'name' => 'required|min:2|max:255',
            'body' => 'required|min:2|max:255',
        ]);
        $campaign->name = $request->name;
        $campaign->type = $request->type;
        $campaign->body = $request->body;
        if ($request->hasFile('mms'))
        {
            $image = $request->file('mms');
            $extension = $image->getClientOriginalExtension();
            $filename = rand(111, 99999) . '.' .  $extension;
            $image_path = 'campaign/' . $filename;
            Image::make($image)->save($image_path);
            $campaign->mms =$filename;
        }
        $campaign->save();

        return redirect()->route('dashboard.campaign.index')->with('success', 'Campaign Updated Successfully');
    }

    public function delete(Campaign $campaign) {
        $campaign->delete();
        return redirect()->route('dashboard.campaign.index')->with('success', 'Campaign Deleted Successfully');
    }




    public function smsCampaign()
    {
        $campaigns = Campaign::all();
        $contacts = Contact::all();
        return view('backend.pages.marketing.campaign.sms-campaign', compact('campaigns', 'contacts'));
    }


    public function sendSms(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'select_custom_contacts' => 'nullable',
            'contacts' => 'required_if:select_custom_contacts,=,true|array',
            'campaign' => 'required',
        ]);

        // Find the campaign by its ID
        $campaign = Campaign::find($request->campaign);
        $campaign_type = $campaign->type;


        if ($request->select_all_contacts)
        {
            // $recipients = Contact::all('phone');
               $recipients = Contact::take(195)->skip(585)->get();

        }elseif ($request->select_custom_contacts)
        {
            $recipients = Contact::whereIn('id', $request->contacts)->get();

        }

        $messageBody = $campaign->body;

        if ($campaign->mms)
        {
            $mmsMediaUrl = asset('/campaign/'.$campaign->mms);
        }


        foreach ($recipients as $recipient) {
            $this->sendMessage($messageBody,$recipient->phone,$mmsMediaUrl,$campaign_type);
        }


        return redirect()
            ->route('dashboard.campaign.sms.campaign')
            ->with('success', 'Campaign Sent Successfully');
    }


    private function sendMessage($messageBody,$recipients,$mediaUrl = null,$campaign_type)
    {
        $account_sid = config('services.twilio.sid');
        $auth_token = config('services.twilio.auth_token');
        $twilio_number = config('services.twilio.number');

        $phone  = str_replace(['(', ')', '-', " "], '', $recipients);

        if ($campaign_type === 'sms')
        {
            try {
                $client = new Client($account_sid, $auth_token);
                $get_response =  $client->messages->create($phone,
                    [
                        'from' => $twilio_number,
                        'body' => $messageBody,
                    ] );
                if ($get_response->status == 'queued' || $get_response->status == 'accepted') {
                    $get_sms_status = 'Delivered|'.$get_response->sid;

                } else {
                    $get_sms_status = $get_response->status.'|'.$get_response->sid;

                }
                
            }catch (\Throwable $exception) {
                log::error('Error Occurred'.$exception->getMessage());
            }
        }

        if ($campaign_type === 'mms')
        {

            try {
                $client = new Client($account_sid, $auth_token);
                $get_response =  $client->messages->create($phone,
                    [
                        'from' => $twilio_number,
                        'body' => $messageBody,
                        'mediaUrl' => $mediaUrl,
                    ] );

                if ($get_response->status == 'queued' || $get_response->status == 'accepted') {
                    $get_sms_status = 'Delivered|'.$get_response->sid;

                } else {
                    $get_sms_status = $get_response->status.'|'.$get_response->sid;

                }



            }catch (\Throwable $exception) {
                log::error('Error Occurred'.$exception->getMessage());
            }

        }




    }

}
