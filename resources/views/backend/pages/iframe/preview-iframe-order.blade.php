@extends('backend.layouts.app')
@section('title')
    Preview Iframe Order
@endsection
@section('contents')
    <div class="col-xl-12">
        @if(Session::has('success'))
            <div class="alert alert-success" role="alert">
                <i class="ri-check-double-line me-3 align-middle"></i>  <strong>Success!</strong>{{session()->get('success')}}.
            </div>
        @endif
            <div class="row  justify-content-center">

                <div class="col-md-12">

                    <div class="card mb-5">
                        <div style="text-align: end">
                            <a href="{{route('edit.iframe.order',[$itira->uuid,$saleData->id])}}" class="btn btn-success m-3">Edit Iframe</a>
                        </div>
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-danger">Itira Data</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <tbody>
                                <tr>
                                    <th>Origin Country</th>
                                    <td>{{$itira->origin_country_name}}</td>
                                </tr>

                                <tr>
                                    <th>Destination Country</th>
                                    <td>

                                        <ol>
                                            @foreach($itira->destinations as $destination)
                                                <li>{{$destination->country_name}}</li>
                                            @endforeach
                                        </ol>

                                    </td>
                                </tr>

                                <tr>
                                    <th>Coverage Start Date</th>
                                    <td>{{$itira->departure_date}}</td>
                                </tr>
                                <tr>
                                    <th>Coverage End Date</th>
                                    <td>{{$itira->arrival_date}}</td>
                                </tr>
                                <tr>
                                    <th>Trip Cost</th>
                                    <td>{{$itira->trip_cost}}</td>
                                </tr>
                                <tr>
                                    <th>First Payment Date</th>
                                    <td>{{$itira->first_payment_date}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card mb-5">
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-danger">Traveler Data</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">DOB</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Zip Code</th>
                                    <th scope="col">Citizenship</th>
                                    <th scope="col">Residence</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($travelersData as $key => $travelerData)
                                    <tr>
                                        <th scope="row">{{$key + 1}}</th>
                                        <td>{{$travelerData->first_name}}</td>
                                        <td>{{$travelerData->last_name}}</td>
                                        <td>{{$travelerData->dob}}</td>
                                        <td>{{$travelerData->email}}</td>
                                        <td>{{$travelerData->phone ?? ''}} </td>
                                        <td>{{$travelerData->zipcode}}</td>
                                        <td>{{$travelerData->citizen->name}}</td>
                                        <td>{{$travelerData->resident->name}}</td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="ShowTravelerModel('{{$travelerData->uuid}}')">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card mt-5">
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-danger">Sales Data</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover">

                                <tbody>
                                <tr>
                                    <th scope="col">Sale Hash</th>
                                    <td>{{$saleData->sale_hash}}</td>
                                </tr>

                                <tr>
                                    <th scope="col">Quote Name</th>
                                    <td>{{$saleData->quote_hash}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Amount</th>
                                    <td>{{$saleData->amount}}</td>
                                </tr>
                                @if($saleData->plan_id)
                                    <tr>
                                        <th scope="col">Plan</th>
                                        <td>{{$saleData->plan->name}}( {{$saleData->plan->min_age}} - {{$saleData->plan->max_age}})</td>
                                    </tr>
                                @else
                                    <tr>
                                        <th scope="col">Plan</th>
                                        <td>{{$saleData->plan_name}}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th scope="col">Certificate Number</th>
                                    <td>{{$saleData->certificate_number}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Coverage</th>
                                    <td>{{$saleData->selected_coverage}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Deductible</th>
                                    <td>{{$saleData->selected_deductible}}</td>
                                </tr>

                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-danger">Sales Traveler Information</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
                                    <th scope="col">DOB</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Policy Number</th>
                                    <th scope="col">Zip Code</th>
                                    <th scope="col">Is Primary</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach( $saleData->travelers as $key => $traveler)
                                    <tr>
                                        <th scope="row">{{$key + 1}}</th>
                                        <td>{{$traveler['first_name']}}</td>
                                        <td>{{$traveler['last_name']}}</td>
                                        <td>{{$traveler['dob']}}</td>
                                        <td>{{$traveler['email']}}</td>
                                        <td>{{$traveler['policy_number'] ?? ''}}</td>
                                        <td>{{$traveler['zipcode']}}</td>
                                        <td>
                                            @if($traveler['is_primary'] == true)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-danger">No</span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-danger">Sales Beneficiary Information</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <tbody>
                                <tr>
                                    <th scope="col">First Name</th>
                                    <td>{{$saleData->beneficiary_info['first_name']}}</td>
                                </tr>

                                <tr>
                                    <th scope="col">Last Name</th>
                                    <td>{{$saleData->beneficiary_info['last_name']}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Email</th>
                                    <td>{{$saleData->beneficiary_info['email']}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Relation</th>
                                    <td>{{$saleData->beneficiary_info['relation']}}</td>
                                </tr>

                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-danger">Sales Payment Information</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover">
                                <tbody>
                                <tr>
                                    <th scope="col">Card Number</th>
                                    <td>{{$saleData->payment_details['card_number']}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Card Name</th>
                                    <td>{{$saleData->payment_details['card_name']}}</td>
                                </tr>

                                <tr>
                                    <th scope="col">Country</th>
                                    <td>{{$saleData->payment_details['country']}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">State</th>
                                    <td>{{$saleData->payment_details['state']}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">City</th>
                                    <td>{{$saleData->payment_details['city']}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Address</th>
                                    <td>{{$saleData->payment_details['address']}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Phone</th>
                                    <td>{{$saleData->payment_details['phone'] ?? ''}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Zip</th>
                                    <td>{{$saleData->payment_details['zip']}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0 text-danger">Sales Billing Information</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-hover">

                                <tbody>
                                <tr>
                                    <th scope="col">Country</th>
                                    <td>{{$saleData->billing_info['country']}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">State</th>
                                    <td>{{$saleData->billing_info['state']}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">City</th>
                                    <td>{{$saleData->billing_info['city']}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Address</th>
                                    <td>{{$saleData->billing_info['address']}}</td>
                                </tr>

                                <tr>
                                    <th scope="col">Zip</th>
                                    <td>{{$saleData->billing_info['zip']}}</td>
                                </tr>
                                <tr>
                                    <th scope="col">Payment Status</th>
                                    @if($saleData->payment_status == 'pending')
                                        <td><span class="badge bg-warning">Pending</span></td>
                                    @elseif($saleData->payment_status == 'success')
                                        <td><span class="badge bg-success">Success</span></td>
                                    @elseif($saleData->payment_status == 'failed')
                                        <td><span class="badge bg-danger">Failed</span></td>
                                    @endif
                                </tr>

                                </tbody>
                            </table>
                            <div class="float-end">
                                <a href="{{route('edit.iframe.order',[$itira->uuid,$saleData->id])}}" class="btn btn-success m-3">Edit Iframe</a>
                            </div>
                        </div>


                    </div>

                </div>
            </div>


        <div id="show_traveler_modal"></div>

@endsection
@push('scripts')
<script>
    function ShowTravelerModel(id)
    {
        $.ajax({
            url: '{{route('edit.iframe.traveler')}}',
            type: "GET",
            data: {
                "_token": "{{ csrf_token() }}",
                'id': id,
                'sale_id': '{{$saleData->id}}'
            },
            success: function (response) {

                $("#show_traveler_modal").html(response);
                $("#traveler_modal").modal('show');
            },
            error: function (e) {
                console.log('error', e);
            }
        });
    }

</script>
@endpush
