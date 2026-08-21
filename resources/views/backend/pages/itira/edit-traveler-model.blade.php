<!-- Modal -->
<div class="modal fade" id="traveler_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Traveler</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('update.iframe.traveler',$traveler->uuid)}}" method="post">
                @csrf
                <input type="hidden" value="{{$salesID}}" name="sales_id">
            <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="traveler_first_name" class="form-label">First Name</label><span class="text-danger">*</span>
                            <input required
                                   class="form-control  @error('travelers.*.traveler_first_name') is-invalid @enderror"
                                   value="{{$traveler->first_name}}" type="text" name="traveler_first_name"
                                   id="traveler_first_name" placeholder="First Name"
                                   style="margin-right: 16px"/>

                            @error('travelers.*.traveler_first_name')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="traveler_last_name" class="form-label mt-2">Last Name</label><span class="text-danger">*</span>
                            <input required
                                   class="form-control  @error('travelers.*.traveler_last_name') is-invalid @enderror"
                                   value="{{$traveler->last_name}}" type="text" name="traveler_last_name"
                                   id="traveler_last_name" placeholder="Last Name"/>
                            @error('travelers.*.traveler_last_name')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <label for="traveler_citizenship" class="form-label">Citizenship</label><span class="text-danger">*</span>
                            <select
                                class="form-select citizenship @error('traveler_citizenship') is-invalid @enderror"
                                id="traveler_citizenship" name="traveler_citizenship">
                                <option selected disabled>Select Citizenship</option>
                                @foreach($countries as $key => $value)
                                    <option value="{{$key}}" {{ $traveler->citizenship == $key ? 'selected' : ''}}>{{$value}}</option>
                                @endforeach
                            </select>
                            @error('traveler_citizenship')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror

                        </div>
                        <div class="col-md-6">

                            <label for="traveler_residence" class="form-label mt-2">Residence</label><span class="text-danger">*</span>
                            <select
                                class="form-select residence @error('traveler_residence') is-invalid @enderror"
                                id="traveler_residence" name="traveler_residence">
                                <option selected disabled>Select Residence</option>
                                @foreach($countries as $key => $value)
                                    <option
                                        value="{{$key}}" {{$traveler->residence == $key ? 'selected' : ''}}>{{$value}}</option>
                                @endforeach
                            </select>
                            @error('traveler_residence')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        </div>
                    <div class="row">
                        <div class="col-md-6">

                            <label for="traveler_zipcode" class="form-label">Zip Code</label><span class="text-danger">*</span>
                            <input required
                                   class="form-control @error('traveler_zipcode') is-invalid @enderror"
                                   value="{{$traveler->zipcode}}" type="text" name="traveler_zipcode"
                                   id="traveler_zipcode" placeholder="ZipCode"
                                   style="margin-right: 16px"/>
                            @error('traveler_zipcode')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6">

                            <label for="traveler_dob" class="form-label mt-2">Date Of Birth</label><span class="text-danger">*</span>
                            <input required
                                   class="form-control @error('traveler_dob') is-invalid @enderror"
                                   value="{{$traveler->dob}}" type="date" name="traveler_dob" id="traveler_dob"
                                   placeholder="Date Of Birth"/>
                            @error('traveler_dob')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">

                            <label for="traveler_email" class="form-label">Email</label><span class="text-danger">*</span>
                            <input required
                                   class="form-control  @error('traveler_email') is-invalid @enderror"
                                   value="{{$traveler->email}}" type="email" name="traveler_email"
                                   id="traveler_email" placeholder="Email" style="margin-right: 16px"/>
                            @error('traveler_email')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror

                        </div>
                        <div class="col-md-6">
                            <label for="traveler_phone" class="form-label mt-2">Phone</label>
                            <input required class="form-control @error('traveler_phone') is-invalid @enderror"
                                   value="{{$traveler->phone}}" type="text" name="traveler_phone" id="traveler_phone" placeholder="Phone"/>
                            @error('traveler_phone')
                            <div class="alert alert-danger">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
