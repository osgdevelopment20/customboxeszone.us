<div>

    <form {{--action="{{route('dashboard.campaign.send.sms')}}" method="post" --}}>
{{--        @csrf--}}
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">SMS Campaign</h4>
            </div>
            <div class="card-body">
                <div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="campaign" class="form-label">Select Campaign</label> <span class="text-danger">*</span>
                            <select wire:model="campaign"  class="form-select @error('campaign') is-invalid @enderror"
                                     id="campaign" name="campaign_id">
                                <option   value=""  disabled selected>Select Campaign</option>
                                @foreach($campaignData as  $campaign)
                                    <option value="{{$campaign->id}}" >{{$campaign->name}}</option>
                                @endforeach
                            </select>
                            @error('campaign')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <label for="contacts" class="form-label">Select Contacts</label><span class="text-danger">*</span>
                            <select wire:model="contacts" class="form-select @error('contacts') is-invalid @enderror"
                                    multiple="multiple" id="contacts" name="contacts_id[]">
                                @foreach($contactsData as $key => $contact)
                                    <option   value="{{$contact->id}}"  @if(in_array($contact->id, old('contacts', []))) selected @endif>{{$contact->name .' ('.$contact->phone.')'}}</option>
                                @endforeach
                            </select>
                            @error('contacts')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-3" style="text-align:end">
                <button type="submit" wire:click="sendSms"  class="btn rounded-pill btn-primary waves-effect waves-light">
                    Send SMS
                    <div wire:loading class="ml-2 spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                </button>
            </div>
        </div>
    </form>
</div>
