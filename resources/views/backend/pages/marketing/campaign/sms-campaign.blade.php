@extends('backend.layouts.app')
@section('title')
   SMS Campaign
@endsection
@section('contents')
    <div class="col-xl-12">
{{--        <livewire:send-sms />--}}

        <form action="{{route('dashboard.campaign.send.sms')}}" method="post" >
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">SMS Campaign</h4>
                </div>
                <div class="card-body">
                    <div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <label for="campaign" class="form-label">Select Campaign</label> <span class="text-danger">*</span>
                                <select  class="form-select @error('campaign') is-invalid @enderror"
                                          id="campaign" name="campaign">
                                    <option   value=""  disabled selected>Select Campaign</option>
                                    @foreach($campaigns as $key => $campaign)
                                        <option   value="{{$campaign->id}}" >{{$campaign->name}}</option>
                                    @endforeach
                                </select>
                                @error('campaign')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="row mt3">
                            <div class="col-md-12">
                                <div class="form-check mt-3">
                                    <input class="form-check-input" onchange="selectAllContacts()" type="checkbox" name="select_all_contacts" id="select_all_contacts"/>
                                    <label class="form-check-label" for="select_all_contacts">
                                        Select All Contacts
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" onchange="selectCustomContacts()" type="checkbox" name="select_custom_contacts" id="select_custom_contacts"/>
                                    <label class="form-check-label" for="select_custom_contacts">
                                        Select Custom Contacts
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3 d-none" id="contacts-list">
                            <div class="col-12">
                                <label for="contacts" class="form-label ">Select Contacts</label><span class="text-danger">*</span>
                                <select class="form-select @error('contacts') is-invalid @enderror"
                                         multiple="multiple" id="contacts" name="contacts[]">
                                    @foreach($contacts as $key => $contact)
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
                    <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light">
                        Send SMS
                    </button>
                </div>
            </div>
        </form>

    </div>
@endsection
@push('scripts')
    <script>
        $('#campaign').select2({
            placeholder: "Select Campaign",
            allowClear: true
        });
        $('#contacts').select2({
            placeholder: "Select Contacts",
            allowClear: true
        });
        $( document ).ready(function() {
            let selectCustomContacts = $('#select_custom_contacts').is(":checked");
           if (selectCustomContacts) {
               $('#contacts-list').removeClass('d-none');
               $('#select_all_contacts').prop( "checked", false );
           }
        });
         function selectCustomContacts(){
            let selectCustomContacts = $('#select_custom_contacts').is(":checked");
            if(selectCustomContacts){
                $('#contacts-list').removeClass('d-none');
                $('#select_all_contacts').prop( "checked", false );
            }
        }
        function selectAllContacts(){
            let selectAllContacts = $('#select_all_contacts').is(":checked");
            if(selectAllContacts){
                $('#contacts-list').addClass('d-none');
                $('#select_custom_contacts').prop( "checked", false );
            }
        }
    </script>
@endpush
