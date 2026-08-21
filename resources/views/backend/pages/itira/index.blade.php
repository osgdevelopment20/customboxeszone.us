@extends('backend.layouts.app')
@section('title')
    Itira
@endsection

@section('contents')
    <div class="col-xl-12">
{{--        <livewire:itira-form />--}}


            <form action="{{route('dashboard.itira.get.plans')}}"  method="post" enctype="multipart/form-data">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Travel Reasons</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <label for="travel_reasons" class="form-label">Select Travel Reasons</label>
                                    <select class="form-select @error('travel_reasons') is-invalid @enderror"
                                            multiple="multiple" id="travel_reasons" name="travel_reasons[]">
                                        @foreach($itiraTravelReasons as $key => $value)
                                            <option   value="{{$key}}"  @if(in_array($key, old('travel_reasons', []))) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                    @error('travel_reasons')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <label for="origin_country_id" class="form-label">Origin Country</label><span class="text-danger">*</span>
                                    <select  wire:model="origin_country_id"   class="form-select  @error('origin_country_id') is-invalid @enderror"
                                             id="origin_country_id" name="origin_country_id">
                                        @foreach($countries as $key => $value)
                                            <option value="{{$key}}" {{old ('origin_country_id') == $key || 'United States' == $value  ? 'selected' : ''}}>{{$value}}</option>
                                        @endforeach
                                    </select>
                                    @error('origin_country_id')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="destination_country_id" class="form-label">Destination Country</label><span class="text-danger">*</span>
                                    <select  wire:model="destination_country_id"    class="form-select @error('destination_country_id') is-invalid @enderror"
                                             multiple="multiple" id="destination_country_id" name="destination_country_id[]">
                                        @foreach($countries as $key => $value)
                                            <option   value="{{$key}}"  @if(in_array($key, old('destination_country_id', []))) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                    @error('destination_country_id')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-6">
                                    <label for="start_date" class="form-label">Coverage Start Date</label><span class="text-danger">*</span>
                                    <input   type="text" id="start_date"
                                           class="form-control start_date flatpickr-input  @error('start_date') is-invalid @enderror"
                                           value="{{old('start_date')}}" name="start_date"
                                           placeholder="Coverage Start Date">
                                    @error('start_date')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="end_date" class="form-label">Coverage End Date</label><span class="text-danger">*</span>
                                    <input  type="text" class="form-control end_date flatpickr-input  @error('end_date') is-invalid @enderror"
                                           value="{{old('end_date')}}" id="end_date" name="end_date"
                                           placeholder="Coverage End Date">
                                    @error('end_date')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <label for="trip_cost" class="form-label">Trip Cost</label>
                                    <input    type="number" min="0" id="trip_cost"
                                            class="form-control @error('trip_cost') is-invalid @enderror"
                                            value="{{old('trip_cost')}}" name="trip_cost" placeholder="Trip Cost">
                                    @error('trip_cost')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="first_payment_date" class="form-label">First Payment Date</label>
                                    <input  type="text"
                                            class="form-control flatpickr-input first_payment_date @error('first_payment_date') is-invalid @enderror"
                                            value="{{old('first_payment_date')}}" id="first_payment_date"
                                            name="first_payment_date" placeholder="First Payment Date">
                                    @error('first_payment_date')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <label for="itira_cards" class="form-label">Credit Cards</label>
                                    <select wire:model="itira_cards" multiple="multiple"  class="form-select  @error('itira_cards') is-invalid @enderror"
                                            id="itira_cards" name="itira_cards[]">
                                        <option value="">Select Credit Cards</option>
                                        @foreach($creditCard as $key => $value)
                                            <option value="{{$key}}" @if(in_array($key, old('credit_card', []))) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                    @error('credit_card')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <label for="number_of_trips" class="form-label">How many trips do you expect to take in the next year?</label><span class="text-danger">*</span>
                                    <input    type="number" min="0" max="100"  id="number_of_trips"
                                            class="form-control @error('no_of_trips') is-invalid @enderror"
                                            value="{{old('no_of_trips')}}" name="no_of_trips"
                                            placeholder="Number of Trips">
                                    @error('no_of_trips')
                                    <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>


                            </div>



                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Traveler Detail</h4>
                    </div>
                    <div class="card-body">
                            <p class="text-primary">Traveler: 1</p>
                            <div id="formRepeater">
                                <div class="border-bottom-dashed ">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="traveler_residence" class="form-label">Residence</label><span class="text-danger">*</span>
                                            <select
                                                    class="form-select residence @error('traveler_residence') is-invalid @enderror"
                                                    id="traveler_residence" name="travelers[1][traveler_residence]">
                                                <option selected disabled>Select Residence</option>
                                                @foreach($countries as $key => $value)
                                                    <option
                                                             value="{{$key}}" {{old ('traveler_residence') == $key || 'United States' == $value ? 'selected' : ''}}>{{$value}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('traveler_residence')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 " id="zipcode">
                                            <label for="traveler_zipcode" class="form-label">Zip Code</label>
                                            <input
                                                    class="form-control @error('traveler_zipcode') is-invalid @enderror"
                                                    value="{{old('traveler_zipcode')}}" type="text" name="travelers[1][traveler_zipcode]"
                                                    id="traveler_zipcode" placeholder="ZipCode"
                                                    style="margin-right: 16px"/>
                                            @error('traveler_zipcode')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">

                                            <label for="traveler_dob" class="form-label">Date Of Birth</label><span class="text-danger">*</span>
                                            <input
                                                    class="form-control  flatpickr-input-dob  @error('traveler_dob') is-invalid @enderror"
                                                    value="{{old('travelers.*.traveler_dob')}}" onchange="dob()" type="text" name="travelers[1][traveler_dob]" id="traveler_dob"
                                                    placeholder="Date Of Birth"/>
                                            <p class="text-danger mt-1" id="calculated-dob"></p>
                                            @error('travelers.*.traveler_dob')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label for="sports" class="form-label">Select Sports</label>
                                            <select   class="form-select @error('sports') is-invalid @enderror"
                                                     multiple="multiple" id="sports" name="travelers[1][sports][]">
                                                @foreach($sports as $key => $value)
                                                    <option  value="{{$key}} " @if(in_array($key, old('travelers.*.sports', []))) selected @endif>{{$value}}</option>
                                                @endforeach
                                            </select>
                                            @error('sports')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check mt-4">
                                                <label class="form-check-label">Pre-ex Conditions
                                                    <input   class="form-check-input @error('pre_ex_conditions') is-invalid @enderror" value="0"
                                                            type="checkbox"  name="travelers[1][pre_ex_conditions]"/>
                                                </label>
                                            </div>
                                            @error('pre_ex_conditions')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>


                        <a href="javascript:void(0);" id="addButton"  class="btn btn-success mt-3" title="Add field"> Add Traveler</a>
{{--                          @if(!old('travelers'))--}}
{{--                            --}}
{{--                            @endif--}}
{{--                        @foreach(old('travelers', []) as $traveler)--}}
{{--                            @error('travelers.*.traveler_first_name')--}}
{{--                           --}}
{{--                            @endforeach--}}
                        <div class="float-end mt-3">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Get Recommended Plans </button>
                        </div>

                        </div>

                    </div>





            </form>


    </div>
@endsection
@push('scripts')
    <script>

        $('#traveler_dob').flatpickr({
            altInput: true
        })


        // Form repeater fields
        let repeaterCount = 0;
        const formRepeater = document.getElementById('formRepeater');
        const addButton = document.getElementById('addButton');
        // Add new repeated form fields
        addButton.addEventListener('click', () => {
            repeaterCount++;
            const newRow = document.createElement('div');
            newRow.classList.add('repeater-row');
            newRow.innerHTML = `
             <div class="border-bottom-dashed">
             <p class="text-primary mt-3">Traveler: ${repeaterCount + 1}</p>
             <div class="row mt-3 ">
                <div class="col-md-4">
                <label for="traveler_residence" class="form-label">Residence</label><span class="text-danger">*</span>
                <select class="form-select residence" onchange="selectResidence(${repeaterCount})" id="traveler-residence-${repeaterCount}" name="travelers[${repeaterCount + 1}][traveler_residence]">
                <option selected disabled>Select Residence</option>
                 @foreach($countries as $key => $value)
                 <option value="{{$key}}" {{ 'United States' == $value ? 'selected' : ''}}>{{$value}}</option>
                  @endforeach
                </select>
               </div>
             <div class="col-md-4 " id="zipcode-${repeaterCount}">
              <label for="traveler_zipcode" class="form-label">Zip Code</label>
               <input class="form-control"  value="" type="text" name="travelers[${repeaterCount + 1}][traveler_zipcode]"
                  id="traveler-zipcode-${repeaterCount}" placeholder="ZipCode" style="margin-right: 16px"/>
              </div>
                 <div class="col-md-4">
                 <label for="traveler_dob" class="form-label">Date Of Birth</label><span class="text-danger">*</span>
                 <input class="form-control  flatpickr-input-dob" value="" type="text" onchange="multiTravelerDob(${repeaterCount})" name="travelers[${repeaterCount + 1}][traveler_dob]" id="traveler-dob-${repeaterCount}"
                    placeholder="Date Of Birth"/>
                  <p class="text-danger mt-1" id="calculated-dob-${repeaterCount}"></p>
                  </div>
              </div>

                  <div class="row mb-3">
                   <div class="col-md-4">
                    <label for="sports" class="form-label">Select Sports</label>
                     <select  class="form-select " multiple="multiple" id="sports-${repeaterCount}" name="travelers[${repeaterCount + 1}][sports][]">
                      @foreach($sports as $key => $value)
                       <option value="{{$key}}" {{old ('sports') == $key}}>{{$value}}</option>
                        @endforeach
                        </select>
                     </div>
              <div class="col-md-4">
                <div class="form-check mt-4">
                <label class="form-check-label">Pre-ex Conditions
                <input class="form-check-input " value="0" type="checkbox" name="travelers[${repeaterCount + 1}][pre_ex_conditions]"/>
                  </label>
                </div>
            </div>
              <div class="col-md-4">
              <a href="javascript:void(0);" onclick="removeRow(${repeaterCount})" id="removeButton-${repeaterCount}" class="  btn btn-danger mt-4" title="Add field"> Remove</a>
                </div>
            </div>

             </div>
              </div>

`;

            formRepeater.appendChild(newRow);
            flatpickr(`#traveler-dob-${repeaterCount}`);
            $(`#traveler-residence-${repeaterCount}`)
                .select2({
                    placeholder: 'Select Residence Country',
                    allowClear: true
                });

            $(`#sports-${repeaterCount}`)
                .select2({
                    placeholder: 'Select Sports ',
                    allowClear: true
                });




        });
        function multiTravelerDob(repeaterCount)
        {
            let dob = $(`#traveler-dob-${repeaterCount}`).val();
            console.log(dob);
            calulatedDob = getAge(dob);
            $(`#calculated-dob-${repeaterCount}`).text(`Traveler Age: ${calulatedDob}`);
            console.log(calulatedDob);
        }

        function dob()
        {
            let dob = $('#traveler_dob').val();
            calulatedDob = getAge(dob);
            $('#calculated-dob').text(`Traveler Age: ${calulatedDob}`);
            console.log(calulatedDob);
        }

        function getAge(dateVal) {
            birthday = new Date(dateVal),
                today = new Date(),
                // console.log('Birthday',birthday);
                //  console.log('today',today);
                ageInMilliseconds = new Date(today - birthday),
                years = ageInMilliseconds / (24 * 60 * 60 * 1000 * 365.25 ),
                months = 12 * (years % 1),
                days = Math.floor(30 * (months % 1));
            return Math.floor(years) + ' years ' + Math.floor(months) + ' months ' + days + ' days';

        }


        function selectResidence(repeaterCount)
        {
            let selectedText = $(`#traveler-residence-${repeaterCount}`).find('option:selected').text();

            if(selectedText !== 'United States')
            {

                $(`#zipcode-${repeaterCount}`).addClass('d-none')
            }else
            {

                $(`#zipcode-${repeaterCount}`).removeClass('d-none')
            }

        }

        function removeRow(repeaterCount){
            if(repeaterCount > 0) {
                if (confirm('Are you sure you want to delete this element?')) {
                    formRepeater.removeChild(formRepeater.lastElementChild);
                    repeaterCount--;
                }
            }
        }

    </script>

    <script>

        $(document).ready(function () {

            $('#traveler_residence').select2({
                placeholder: "Select Residence Country",
                allowClear: true
            });
             $('#sports').select2({
                placeholder: "Select Sports",
                allowClear: true
            });

            $('#itira_cards').select2({
                placeholder: "Select Credit Cards",
                allowClear: true
            });



            $('#traveler_residence').change(function() {
                let selectedText = $(this).find('option:selected').text();
                console.log(selectedText)
                if(selectedText !== 'United States')
                {
                    $(`#zipcode`).addClass('d-none')
                }else
                {
                    $(`#zipcode`).removeClass('d-none')
                }
                console.log(selectedText);

            });


            $('#destination_country_id').select2({
                placeholder: "Select Destination Country",
                allowClear: true
            });
             $('#credit_card').select2({
                placeholder: "Select Credit Card",
                allowClear: true
            });

            $('#origin_country_id').select2({
                placeholder: "Select Origin Country",
                allowClear: true
            });
            $('#travel_reasons').select2({
                placeholder: "Select Travel Reasons",
                allowClear: true
            });

            flatpickr(".start_date",{
                minDate: "today"
            });

            flatpickr(".end_date",{
                minDate: "today"
            });

            flatpickr(".first_payment_date",{
                minDate: "today"
            });


        });

    </script>




@endpush
