@extends('backend.layouts.app')
@section('title')
   Add Campaign
@endsection
@section('contents')
    <div class="col-xl-12">
        <form action="{{route('dashboard.campaign.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Campaign</h4>
                </div>
                <div class="card-body">
                    <div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <label for="name" class="form-label">Name</label> <span class="text-danger">*</span>
                                <input type="text"  id="name"
                                       class="form-control  @error('name') is-invalid @enderror"
                                       value="{{old('name')}}" name="name"
                                       placeholder="Name">
                                @error('name')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="type" class="form-label">Campaign Type</label> <span class="text-danger">*</span>
                                <select onchange="campaignType()"  class="form-select @error('type') is-invalid @enderror"
                                         id="type" name="type">
                                    <option value="sms" selected>SMS</option>
                                    <option value="mms">MMS</option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-3 d-none" id="campaign_mms">
                            <div class="col-12">
                                <label for="mms" class="form-label">Campaign Image for MMS</label>
                                <input class="form-control" name="mms" type="file" id="mms">
                                @error('mms')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="row mt-3" id="campaign_body">
                            <div class="col-12">
                                <label for="body" class="form-label">Campaign Body</label> <span class="text-danger">*</span>
                                <textarea id="body" class="form-control  @error('body') is-invalid @enderror" name="body"
                                  rows="10"     placeholder="Campaign Body"> {{old('body')}}</textarea>
                                @error('body')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>


                    </div>
                </div>
                <div class="m-3" style="text-align:end">
                    <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light">
                        Save
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')

    <script>

        function campaignType(){
            let selectedType = $('#type').find('option:selected').val();
            if (selectedType === 'sms') {
                $('#campaign_body').removeClass('d-none');
                $('#campaign_mms').addClass('d-none');
            } else {
                // $('#campaign_body').addClass('d-none');
                $('#campaign_mms').removeClass('d-none');
            }
        }

    </script>

@endpush
