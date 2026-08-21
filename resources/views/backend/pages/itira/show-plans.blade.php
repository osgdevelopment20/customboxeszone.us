@extends('backend.layouts.app')
@section('title')
    Recommended Plans
@endsection
@section('contents')
    <div class="col-xl-12">
        @if(Session::has('success'))
            <div class="alert alert-success" role="alert">
                <i class="ri-check-double-line me-3 align-middle"></i>  <strong>Success!</strong>{{session()->get('success')}}.
            </div>
        @endif
            <div class="row mb-3">
                <div class="col-xl-8">
                    @if($getPlan['records']['plans'])
                    <div class="row align-items-center gy-3 mb-3">
                        <div class="col-sm">
                            
                            <div>
                                <h5 class="fs-14 mb-0">Total Recommended Plans ({{count($getPlan['records']['plans'])}} Plans)</h5>
                            </div>
                        </div>
                    </div>
                        <div class="mb-3 ">
                            <a href="javascript:void(0);" onclick="copyLinkToClipboard('{{$shareAbleLink}}')" class="btn btn-sm btn-primary waves-effect waves-light">Copy Link</a>
                        </div>
                    @foreach($getPlan['records']['plans'] as $key => $plan)

                    <div class="card product">
                        <div class="card-body">
                            <div class="row gy-3">
                                <div class="col-sm-auto">
                                    <div class="avatar-lg bg-light rounded p-1">
                                 <img src="{{json_decode($plan['PlanImages'], true)['img1'] ?? ''}}" alt="plan-image" class="img-fluid d-block">
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <h5 class="fs-14 text-truncate"><a href="javascript:void(0);" class="text-dark">{{$plan['PlanName']}}</a></h5>
                                    <ul class="text-muted">
                                        <li class="">Product Name : <span class="fw-medium">{{$plan['ProductName']}}</span></li>
                                        <li class="">Administrator : <span class="fw-medium">{{$plan['Administrator']}}</span></li>
                                        <li class="">Underwriter : <span class="fw-medium">{{$plan['Underwriter']}}</span></li>
                                        <li class="">Product Segment : <span class="fw-medium">{{$plan['ProductSegment']}}</span></li>
                                    </ul>

                                    <div class="row">
                                        <div class="col-6">
                                            <label for="coverage-limits-{{$plan['PlanId']}}" class="form-label">Coverage Limits</label>
                                            <select id="coverage-limits-{{$plan['PlanId']}}" onchange="coverageLimits('{{$plan['PlanId']}}')" class="form-select form-select-sm  mb-3" aria-label=".form-select-sm example">
                                                @if($plan['coverage_limits'])
                                                    @foreach($plan['coverage_limits'] as $coverage)
                                                        <option value="{{$coverage}}" >{{$coverage}}</option>
                                                    @endforeach
                                                @else
                                                    <option value="" >This plan have not Coverage</option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label for="deductibles-{{$plan['PlanId']}}" class="form-label">Deductibles</label>
                                            <select id="deductibles-{{$plan['PlanId']}}" onchange="deductibles({{$plan['PlanId']}})" class="form-select form-select-sm  mb-3" aria-label=".form-select-sm example">
                                                @if($plan['deductibles'])
                                                    @foreach($plan['deductibles'] as $deductibles)
                                                        <option  value="{{$deductibles}}" >{{$deductibles}}</option>
                                                    @endforeach
                                                @else
                                                    <option value="" >This plan have not Deductibles</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm">
                                    <div class="text-lg-end">
                                        <ul class="list-unstyled ">
                                            <li class="text-muted">Per Day Cost : <span class="fw-medium text-dark" id="per-day-cost-{{$plan['PlanId']}}">$ {{$plan['price']['per_day_cost']}}</span></li>
                                            <li class="text-muted">Total Trip Days : <span class="fw-medium text-dark" id="total-trip-Days-{{$plan['PlanId']}}">{{$plan['price']['total_trip_Days']}}</span></li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row align-items-center gy-3">
                              <div class="col-sm">
                                    <div class="d-flex flex-wrap my-n1">

                                    </div>
                                </div>
                                <div class="col-sm-auto ">
                                    <div class="d-flex align-items-center gap-2 text-muted ">
                                        <div>Total Price:</div>
                                        <h5 class="fs-14 mb-0"><span class="product-line-price" id="total-cost-{{$plan['PlanId']}}">$ {{$plan['price']['total_cost']}}</span></h5>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach
                    @else
                    <h4>Something Wrong From Backend Side</h4>
                    @endif

                </div>

            </div>

@endsection
@push('scripts')
                @php
                     $travelers = $getPlan['records']['Travellers'];
                     $destination = $getPlan['records']['trip_details']['Destination'];
                     $trip_start_date = $getPlan['records']['trip_details']['StartDate'];
                     $trip_end_date = $getPlan['records']['trip_details']['EndDate'];
                @endphp
                <script>
                    function coverageLimits(id){
                        let coverage = $(`#coverage-limits-${id}`).find('option:selected').val();
                        const travelers = {{ Illuminate\Support\Js::from($travelers) }};
                        const destination ={{ Illuminate\Support\Js::from($destination) }};

                       $.ajax({
                           url: '{{url('api/v1/plan-details')}}',
                           type: 'POST',
                           contentType: 'application/json',
                           dataType: 'json',
                           data: JSON.stringify({
                               plan_id: id,
                               travelers: travelers,
                               destination: destination,
                               trip_start_date: '{{$trip_start_date}}',
                               trip_end_date: '{{$trip_end_date}}',
                               coverage: coverage,
                               _token: '{{ csrf_token() }}'
                           }),
                           success: function(data) {
                               console.log(data);
                               $(`#per-day-cost-${id}`).text('$ '+data.records.price.per_day_cost) ;
                               $(`#total-cost-${id}`).text('$ '+data.records.price.total_cost) ;
                               $(`#total-trip-Days-${id}`).text(data.records.price.total_trip_Days) ;

                               console.log(data.records.price);
                           },
                           error: function(error) {
                               console.log(error);

                           }
                       });
                    }
                    function deductibles(id){
                        let deductible = $(`#deductibles-${id}`).find('option:selected').val();
                        const travelers = {{ Illuminate\Support\Js::from($travelers) }};
                        const destination ={{ Illuminate\Support\Js::from($destination) }};

                        $.ajax({
                            url: '{{url('api/v1/plan-details')}}',
                            type: 'POST',
                            contentType: 'application/json',
                            dataType: 'json',
                            data: JSON.stringify({
                                plan_id: id,
                                travelers: travelers,
                                destination: destination,
                                trip_start_date: '{{$trip_start_date}}',
                                trip_end_date: '{{$trip_end_date}}',
                                deductible: deductible,
                                _token: '{{ csrf_token() }}'
                            }),
                            success: function(data) {
                                console.log(data);
                                $(`#per-day-cost-${id}`).text('$ '+data.records.price.per_day_cost) ;
                                $(`#total-cost-${id}`).text('$ '+data.records.price.total_cost) ;
                                $(`#total-trip-Days-${id}`).text(data.records.price.total_trip_Days) ;

                                console.log(data.records.price);
                            },
                            error: function(error) {
                                console.log(error);

                            }
                        });
                    }
                </script>

                <script>
                    // Function to copy a link to the clipboard
                    function copyLinkToClipboard(link) {
                        // Create a temporary input element
                        const tempInput = document.createElement("input");

                        // Set the input value to the link you want to copy
                        tempInput.value = link;

                        // Append the input element to the document
                        document.body.appendChild(tempInput);

                        // Select the input field
                        tempInput.select();

                        // Copy the selected text to the clipboard
                        document.execCommand("copy");

                        // Remove the temporary input element
                        document.body.removeChild(tempInput);

                        // Optionally, provide some user feedback
                        alert("Link copied to clipboard: " + link);
                    }



                </script>

@endpush
