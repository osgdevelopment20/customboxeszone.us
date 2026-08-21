@extends('backend.layouts.app')
@section('title')
    Iframe Order
@endsection

@section('contents')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0 text-danger">Suggestions</h4>
            </div>
            <div class="card-body">
                <p>Please Select the Plan name according to the max age<br>
                    like between age 0-69 take Atlas America etc <br>
                    between 70-74 take Atlas America 74<br>
                    between 75-79 take Atlas America 79<br>
                    above 80 or 80+ take Atlas America 80 (only for Travel Medical/Frequent Flyer Plans)</p>
            </div>
        </div>


        <form action="{{route('submit.iframe.order')}}" method="post" >
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Trip Detail</h4>
                </div>
                <div class="card-body">
                    <div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <label for="plan"  class="form-label">Plan</label><span class="text-danger">*</span>
                                <span><a href="javascript:void(0)"  onclick="customPlan()" id="custom-plan-btn"  class="btn btn-sm btn-success">Add Custom Plan</a></span>
                                <span><a href="javascript:void(0)" onclick="exitingPlan()" id="existing-plan-btn" class="btn btn-sm btn-warning d-none ">Select Exiting Plan</a></span>
                                <input type="text"  id="custom-plan"
                                       class="form-control d-none @error('custom_plan_name') is-invalid @enderror"
                                       value="{{old('custom_plan_name')}}" name="custom_plan_name"
                                       placeholder="Add Custom Plan">
                                @error('custom_plan_name')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                                <div id="plan-id">


                                <select  class="@error('plan') is-invalid @enderror" onchange="PlanData()"  id="plan" name="plan">
                                    @foreach($plans as $key => $value)
                                        <option value="" disabled selected>Select Plan</option>
                                        <option value="{{$key}}" {{old ('plan') == $key ? 'selected' : ''}}>{{$value}}</option>
                                    @endforeach
                                </select>
                                @error('plan')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="certificate_number" class="form-label">Certificate Number</label> <span class="text-danger">*</span>
                                <input type="text"  id="certificate_number"
                                       class="form-control  @error('certificate_number') is-invalid @enderror"
                                       value="{{old('certificate_number')}}" name="certificate_number"
                                       placeholder="Certificate Number">
                                @error('certificate_number')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <h5 class="text-danger"> Suggestions :</h5> <p>Coverage & Deductible are Optional for Trip Protection & Student Medical</p>
                            <div class="col-6">
                                <label for="coverage" class="form-label">Coverage</label>
                                <input type="text"  id="custom-coverage"
                                       class="form-control d-none @error('custom_coverage') is-invalid @enderror"
                                       value="{{old('custom_coverage')}}" name="custom_coverage"
                                       placeholder="Custom Coverage">
                                <select  class="form-select " id="coverage" name="coverage">
                                    <option selected disabled>Select Coverage</option>

                                </select>
                            </div>
                            <div class="col-6">
                                <label for="deductible" class="form-label">Deductible</label>
                                <input type="text"  id="custom-deductible"
                                       class="form-control d-none @error('custom_deductible') is-invalid @enderror"
                                       value="{{old('custom_deductible')}}" name="custom_deductible"
                                       placeholder="Custom Deductible">
                                <select  class="form-select" id="deductible" name="deductible">
                                    <option selected disabled>Select Deductible</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <label for="start_date" class="form-label">Coverage Start Date</label><span class="text-danger">*</span>
                                <input  type="text" id="start_date"
                                        class="form-control flatpickr-input  @error('start_date') is-invalid @enderror"
                                        value="{{old('start_date')}}" name="start_date"
                                        placeholder="Coverage Start Date">
                                @error('start_date')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="end_date" class="form-label">Coverage End Date</label><span class="text-danger">*</span>
                                <input  type="text" class="form-control flatpickr-input  @error('end_date') is-invalid @enderror"
                                        value="{{old('end_date')}}" id="end_date" name="end_date"
                                        placeholder="Coverage End Date">
                                @error('end_date')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <label for="price" class="form-label">Actual Amount</label><span class="text-danger">*</span>
                                <input  type="number" min="0" class="form-control  @error('price') is-invalid @enderror"
                                        value="{{old('price')}}" id="price" name="price" placeholder="Price">
                                @error('price')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-6">
                                <label for="charged_amount" class="form-label">Charged Amount</label><span class="text-danger">*</span>
                                <input  type="number" min="0" class="form-control  @error('charged_amount') is-invalid @enderror"
                                        value="{{old('charged_amount')}}" id="charged_amount" name="charged_amount" placeholder="Charged Amount">
                                @error('charged_amount')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <label for="origin_country_id" class="form-label">Origin Country</label><span class="text-danger">*</span>
                                <select  class="form-select  @error('origin_country_id') is-invalid @enderror"
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
                                <select  class="form-select @error('destination_country_id') is-invalid @enderror"
                                         multiple="multiple" id="destination_country_id" name="destination_country_id[]">
                                    @foreach($countries as $key => $value)
                                        <option value="{{$key}}"  @if(in_array($key, old('destination_country_id', []))) selected @endif>{{$value}}</option>
                                    @endforeach
                                </select>
                                @error('destination_country_id')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <label for="trip_cost" class="form-label">Trip Cost</label>
                                <input  type="number" min="0" id="trip_cost"
                                        class="form-control @error('trip_cost') is-invalid @enderror"
                                        value="{{old('trip_cost')}}" name="trip_cost" placeholder="Trip Cost">
                                @error('trip_cost')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="first_payment_date" class="form-label">First Payment Date</label><span class="text-danger">*</span>
                                <input  type="text"
                                        class="form-control flatpickr-input  @error('first_payment_date') is-invalid @enderror"
                                        value="{{old('first_payment_date')}}" id="first_payment_date"
                                        name="first_payment_date" placeholder="First Payment Date">
                                @error('first_payment_date')
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
                    <div class="repeater">
                        <div data-repeater-list="travelers">
                            @if(!old('travelers'))
                                <div data-repeater-item>
                                    <h2 class="traveler_counter"></h2>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="traveler_first_name" class="form-label">First Name</label><span class="text-danger">*</span>
                                            <input
                                                class="form-control  @error('travelers.*.traveler_first_name') is-invalid @enderror"
                                                value="{{old('traveler_first_name')}}" type="text" name="traveler_first_name"
                                                id="traveler_first_name" placeholder="First Name"
                                                style="margin-right: 16px"/>
                                            @error('travelers.*.traveler_first_name')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="traveler_last_name" class="form-label">Last Name</label><span class="text-danger">*</span>
                                            <input
                                                class="form-control  @error('travelers.*.traveler_last_name') is-invalid @enderror"
                                                value="{{old('traveler_last_name')}}" type="text" name="traveler_last_name"
                                                id="traveler_last_name" placeholder="Last Name"/>
                                            @error('travelers.*.traveler_last_name')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror

                                        </div>
                                        <div class="col-md-3">

                                            <label for="traveler_citizenship" class="form-label">Citizenship</label><span class="text-danger">*</span>
                                            <select
                                                class="form-select citizenship @error('traveler_citizenship') is-invalid @enderror"
                                                id="traveler_citizenship" name="traveler_citizenship">
                                                <option selected disabled>Select Citizenship</option>
                                                @foreach($countries as $key => $value)
                                                    <option value="{{$key}}" {{old ('traveler_citizenship') == $key || 'United States' == $value  ? 'selected' : ''}}>{{$value}}</option>
                                                @endforeach
                                            </select>
                                            @error('traveler_citizenship')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror

                                        </div>
                                        <div class="col-md-3">

                                            <label for="traveler_residence" class="form-label">Residence</label><span class="text-danger">*</span>
                                            <select
                                                class="form-select residence @error('traveler_residence') is-invalid @enderror"
                                                id="traveler_residence" name="traveler_residence">
                                                <option selected disabled>Select Residence</option>
                                                @foreach($countries as $key => $value)
                                                    <option
                                                        value="{{$key}}" {{old ('traveler_residence') == $key || 'United States' == $value ? 'selected' : ''}}>{{$value}}</option>
                                                @endforeach
                                            </select>
                                            @error('traveler_residence')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-3">

                                            <label for="traveler_zipcode" class="form-label">Zip Code</label><span class="text-danger">*</span>
                                            <input
                                                class="form-control @error('traveler_zipcode') is-invalid @enderror"
                                                value="{{old('traveler_zipcode')}}" type="text" name="traveler_zipcode"
                                                id="traveler_zipcode" placeholder="ZipCode"
                                                style="margin-right: 16px"/>
                                            @error('traveler_zipcode')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">

                                            <label for="traveler_dob" class="form-label">Date Of Birth</label><span class="text-danger">*</span>
                                            <input
                                                class="form-control  flatpickr-input  @error('traveler_dob') is-invalid @enderror"
                                                value="{{old('traveler_dob')}}" type="text" name="traveler_dob" id="traveler_dob"
                                                placeholder="Date Of Birth"/>
                                            @error('traveler_dob')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">

                                            <label for="traveler_email" class="form-label">Email</label><span class="text-danger">*</span>
                                            <input
                                                class="form-control  @error('traveler_email') is-invalid @enderror"
                                                value="{{old('traveler_email')}}" type="email" name="traveler_email"
                                                id="traveler_email" placeholder="Email" style="margin-right: 16px"/>
                                            @error('traveler_email')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror

                                        </div>
                                        <div class="col-md-3">
                                            <label for="traveler_phone" class="form-label">Phone</label>
                                            <input  class="form-control @error('traveler_phone') is-invalid @enderror"
                                                    value="{{old('traveler_phone')}}" type="text" name="traveler_phone" id="traveler_phone" placeholder="Phone"/>
                                            @error('traveler_phone')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-3">

                                            <label for="traveler_zipcode" class="form-label">Policy Number</label><span class="text-danger">*</span>
                                            <input
                                                class="form-control @error('traveler_policy_number') is-invalid @enderror"
                                                value="{{old('traveler_policy_number')}}" type="text" name="traveler_policy_number"
                                                id="traveler_policy_number" placeholder="Policy Number"
                                                style="margin-right: 16px"/>
                                            @error('traveler_policy_number')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check mt-5">

                                                <label class="form-check-label">Is Primary
                                                    <input class="form-check-input @error('traveler_is_primary') is-invalid @enderror" value=""
                                                           type="checkbox" name="traveler_is_primary"/>
                                                </label>
                                            </div>
                                            @error('traveler_is_primary')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <button data-repeater-delete type="button" class="btn btn-danger">Remove</button>
                                    <hr>
                                </div>
                            @endif
                            @foreach(old('travelers', []) as $traveler)
                                <div data-repeater-item>
                                    <h2 class="traveler_counter"></h2>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="traveler_first_name" class="form-label">First Name</label><span class="text-danger">*</span>
                                            <input
                                                class="form-control  @error('travelers.*.traveler_first_name') is-invalid @enderror"
                                                value="{{$traveler['traveler_first_name']}}" type="text" name="traveler_first_name"
                                                id="traveler_first_name" placeholder="First Name"
                                                style="margin-right: 16px"/>

                                            @error('travelers.*.traveler_first_name')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-3">
                                            <label for="traveler_last_name" class="form-label mt-2">Last Name</label><span class="text-danger">*</span>
                                            <input
                                                class="form-control  @error('travelers.*.traveler_last_name') is-invalid @enderror"
                                                value="{{$traveler['traveler_last_name']}}" type="text" name="traveler_last_name"
                                                id="traveler_last_name" placeholder="Last Name"/>
                                            @error('travelers.*.traveler_last_name')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror

                                        </div>
                                        <div class="col-md-3">

                                            <label for="traveler_citizenship" class="form-label">Citizenship</label><span class="text-danger">*</span>
                                            <select
                                                class="form-select citizenship @error('traveler_citizenship') is-invalid @enderror"
                                                id="traveler_citizenship" name="traveler_citizenship">
                                                <option selected disabled>Select Citizenship</option>
                                                @foreach($countries as $key => $value)
                                                    <option value="{{$key}}" {{ $traveler['traveler_citizenship'] == $key ? 'selected' : ''}}>{{$value}}</option>
                                                @endforeach
                                            </select>
                                            @error('traveler_citizenship')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror

                                        </div>
                                        <div class="col-md-3">

                                            <label for="traveler_residence" class="form-label mt-2">Residence</label><span class="text-danger">*</span>
                                            <select
                                                class="form-select residence @error('traveler_residence') is-invalid @enderror"
                                                id="traveler_residence" name="traveler_residence">
                                                <option selected disabled>Select Residence</option>
                                                @foreach($countries as $key => $value)
                                                    <option
                                                        value="{{$key}}" {{$traveler['traveler_residence'] == $key ? 'selected' : ''}}>{{$value}}</option>
                                                @endforeach
                                            </select>
                                            @error('traveler_residence')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">

                                            <label for="traveler_zipcode" class="form-label">Zip Code</label><span class="text-danger">*</span>
                                            <input
                                                class="form-control @error('traveler_zipcode') is-invalid @enderror"
                                                value="{{$traveler['traveler_zipcode']}}" type="text" name="traveler_zipcode"
                                                id="traveler_zipcode" placeholder="ZipCode"
                                                style="margin-right: 16px"/>
                                            @error('traveler_zipcode')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">

                                            <label for="traveler_dob" class="form-label mt-2">Date Of Birth</label><span class="text-danger">*</span>
                                            <input
                                                class="form-control @error('traveler_dob') is-invalid @enderror"
                                                value="{{$traveler['traveler_dob']}}" type="date" name="traveler_dob" id="traveler_dob"
                                                placeholder="Date Of Birth"/>
                                            @error('traveler_dob')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">

                                            <label for="traveler_email" class="form-label">Email</label><span class="text-danger">*</span>
                                            <input
                                                class="form-control  @error('traveler_email') is-invalid @enderror"
                                                value="{{$traveler['traveler_email']}}" type="email" name="traveler_email"
                                                id="traveler_email" placeholder="Email" style="margin-right: 16px"/>
                                            @error('traveler_email')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror

                                        </div>
                                        <div class="col-md-3">
                                            <label for="traveler_phone" class="form-label mt-2">Phone</label>
                                            <input  class="form-control @error('traveler_phone') is-invalid @enderror"
                                                    value="{{$traveler['traveler_phone']}}" type="text" name="traveler_phone" id="traveler_phone" placeholder="Phone"/>
                                            @error('traveler_phone')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">

                                            <label for="traveler_zipcode" class="form-label">Policy Number</label><span class="text-danger">*</span>
                                            <input
                                                class="form-control @error('traveler_policy_number') is-invalid @enderror"
                                                value="{{$traveler['traveler_policy_number']}}" type="text"  name="traveler_policy_number"
                                                id="traveler_policy_number" placeholder="Policy Number"
                                                style="margin-right: 16px"/>
                                            @error('traveler_policy_number')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check mt-5">
                                                <input class="form-check-input @error('traveler_is_primary') is-invalid @enderror"
                                                       value="{{old('traveler_is_primary')}}" type="checkbox" name="traveler_is_primary" id="traveler_is_primary"/>
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    Is Primary
                                                </label>
                                            </div>
                                            @error('traveler_is_primary')
                                            <div class="invalid-feedback">{{$message}}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <button data-repeater-delete type="button" class="btn btn-danger">Remove</button>
                                    <hr>
                                </div>
                            @endforeach

                        </div>
                        <button data-repeater-create type="button" class="btn btn-primary mt-2">Add Traveler</button>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Beneficiary Detail</h4>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="beneficiary_fname" class="form-label">Beneficiary First Name</label><span class="text-danger">*</span>
                            <input  type="text"
                                    class="form-control  @error('beneficiary_fname') is-invalid @enderror"
                                    value="{{old('beneficiary_fname')}}" id="beneficiary_fname"
                                    name="beneficiary_fname" placeholder="Beneficiary First Name">
                            @error('beneficiary_fname')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="beneficiary_lname" class="form-label">Beneficiary Last Name</label><span class="text-danger">*</span>
                            <input  type="text"
                                    class="form-control  @error('beneficiary_lname') is-invalid @enderror"
                                    value="{{old('beneficiary_lname')}}" id="beneficiary_lname"
                                    name="beneficiary_lname" placeholder="Beneficiary Last Name">
                            @error('beneficiary_lname')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="beneficiary_relation" class="form-label">Beneficiary Relations</label><span class="text-danger">*</span>
                            <input  type="text"
                                    class="form-control  @error('beneficiary_relation') is-invalid @enderror"
                                    value="{{old('beneficiary_relation')}}" id="beneficiary_relation"
                                    name="beneficiary_relation" placeholder="Beneficiary Relations">
                            @error('beneficiary_relation')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="beneficiary_email" class="form-label">Beneficiary Email</label><span class="text-danger">*</span>
                            <input  type="text"
                                    class="form-control  @error('beneficiary_email') is-invalid @enderror"
                                    value="{{old('beneficiary_email')}}" id="beneficiary_email"
                                    name="beneficiary_email" placeholder="Beneficiary Email">
                            @error('beneficiary_email')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>


                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Payment Detail</h4>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="card_name" class="form-label">Card Holder Name</label>
                            <input  type="text" class="form-control  @error('card_name') is-invalid @enderror"
                                    value="{{old('card_name')}}" id="card_name" name="card_name"
                                    placeholder="Card Holder Name">
                            @error('card_name')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label for="card_number" class="form-label">Credit Card Last 4 Digit</label><span class="text-danger">*</span>
                            <input  type="text" class="form-control @error('card_number') is-invalid @enderror" value="{{old('card_number')}}"
                                    id="card_number" name="card_number"
                                    placeholder="Credit Card Last 4 Digit">
                            @error('card_number')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="country" class="form-label">Country</label><span class="text-danger">*</span>
                            <select
                                class="form-select country @error('country') is-invalid @enderror"
                                id="country" onchange="GetStatesFromCountries()" name="country">
                                <option selected disabled>Select Country</option>
                                @foreach($countries as $key => $value)
                                    <option value="{{$key}}" {{old ('country') == $key  ? 'selected' : ''}}>{{$value}}</option>
                                @endforeach
                            </select>
                            @error('country')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="state" class="form-label">State</label><span class="text-danger">*</span>
                            <select
                                class="form-select state @error('state') is-invalid @enderror"
                                id="state" onchange="GetCitiesFromStates()" name="state">
                                <option selected disabled>Select State</option>
                            </select>

                            @error('state')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="city" class="form-label">City</label><span class="text-danger">*</span>
                            <input  type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{old('city')}}"
                                    placeholder="City">
                            @error('city')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                        <div class="col-6">
                            <label for="address" class="form-label">Address</label><span class="text-danger">*</span>
                            <input  type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{old('address')}}"
                                    placeholder="Address">
                            @error('address')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="zip" class="form-label">Zip Code</label><span class="text-danger">*</span>
                            <input  type="text" class="form-control  @error('zip') is-invalid @enderror" value="{{old('zip')}}" id="zip" name="zip" placeholder="Zip Code">
                            @error('zip')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label for="purchase_date" class="form-label">Purchase Date</label><span class="text-danger">*</span>
                            <input  type="text" id="purchase_date" class="form-control flatpickr-input @error('purchase_date') is-invalid @enderror" value="{{old('purchase_date')}}" name="purchase_date"
                                    placeholder="Purchase Date">
                            @error('purchase_date')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <label for="payment_status" class="form-label">Payment Status</label><span class="text-danger">*</span>
                            <select
                                class="form-select  @error('payment_status') is-invalid @enderror"
                                id="payment_status"  name="payment_status">
                                <option selected disabled>Select Payment Status</option>
                                <option value="pending">Pending</option>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                            </select>
                            @error('payment_status')
                            <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                    </div>
                 <div class="float-end mt-3">
                 <button type="submit" class="btn btn-primary waves-effect waves-light">Submit </button>
                 </div>
                </div>
            </div>

        </form>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('js/jquery.repeater.js')}}"></script>

    <script>


        $(document).ready(function () {
            flatpickr(".flatpickr-input");
            $('#plan').select2({
                placeholder: "Select Plan",
                allowClear: true
            });
            $('#destination_country_id').select2({
                placeholder: "Select Destination Country",
                allowClear: true
            });
            $('#origin_country_id').select2({
                placeholder: "Select Origin Country",
                allowClear: true
            });
            $('.repeater').repeater({
                show: function () {
                    $(this).slideDown();
                    var travelerCounter = $('.repeater').repeaterVal();
                    console.log(travelerCounter);
                    //   $('.traveler_counter').text('Traveler '+ travelerCounter.travelers.length +'');

                    $('#destination_country_id').select2({
                        placeholder: "Select Destination Country",
                        allowClear: true
                    });
                    $('#origin_country_id').select2({
                        placeholder: "Select Origin Country",
                        allowClear: true
                    });

                },
                hide: function (deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                    }
                },
                ready: function (setIndexes) {

                }
            });

        });

        function customPlan()
        {
            $('#custom-plan').removeClass("d-none")
            $('#custom-coverage').removeClass("d-none")
            $('#custom-deductible').removeClass("d-none")
            $('#existing-plan-btn').removeClass("d-none")


            $('#plan').addClass("d-none")
            $('#plan-id').addClass("d-none")
            $('#coverage').addClass("d-none")
            $('#deductible').addClass("d-none")
            $('#custom-plan-btn').addClass("d-none")

        }
        function exitingPlan()
        {
            $('#custom-plan').addClass("d-none")
            $('#custom-coverage').addClass("d-none")
            $('#custom-deductible').addClass("d-none")
            $('#existing-plan-btn').addClass("d-none")

            $('#plan').removeClass("d-none")
            $('#plan-id').removeClass("d-none")
            $('#coverage').removeClass("d-none")
            $('#deductible').removeClass("d-none")
            $('#custom-plan-btn').removeClass("d-none")


        }
    </script>

    <script>
        function PlanData()
        {
            $('#coverage').find('option').remove().end()
            $('#deductible').find('option').remove().end()
            planID = $('#plan').find(":selected").val()
            // console.log(planID)
            $.ajax({
                type: 'post',
                url: '{{route('get.plan.data')}}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'plan_id': planID,
                },
                success: function (response) {
                      console.log(response.coverage)
                      console.log(response.deductibles)
                   if (response.coverage !== null && response.coverage.length !== 0){
                       $.each(response.coverage, function (index, value) {
                           $('#coverage').append($('<option/>', {
                               value: value,
                               text : value
                           }));
                       });
                   }else {
                       $('#coverage').append($('<option/>',{
                           value: "",
                           text: "Plan have no coverage",
                       }));
                   }
                    if (response.deductibles !== null && response.deductibles.length !== 0) {
                        $.each(response.deductibles, function (index, value) {
                            $('#deductible').append($('<option/>', {
                                value: value,
                                text: value
                            }));
                        });

                    }else {
                        $('#deductible').append($('<option/>', {
                            value: "",
                            text: "Plan have no deductibles",
                        }));
                    }

                },
                error: function (e) {
                    console.log('error', e);
                }
            });
        }
    </script>

    <script>
        function GetStatesFromCountries()
        {
            $('#state').find('option').remove().end()
            $('#city').find('option').remove().end()
            country_id = $('#country').find(":selected").val()
            // console.log(planID)
            $.ajax({
                type: 'post',
                url: '{{route('get.states.data')}}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'country_id': country_id,
                },
                success: function (response) {
                    if (response.status === true){
                        // console.log(response.states)
                        $.each(response.states, function (index, value) {
                            $('#state').append($('<option/>', {
                                value: value,
                                text : value
                            }));
                        });
                    }else if(response.status === false)
                    {
                        $('#state').append('<option selected disabled>States are Not Found</option>');
                    }
                },
                error: function (e) {
                    console.log('error', e);
                }
            });
        }

    </script>

@endpush
