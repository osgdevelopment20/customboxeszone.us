@extends('backend.layouts.app')
@section('title')
    Edit Campaign
@endsection
@section('contents')
    <div class="col-xl-12">
        <form action="{{route('dashboard.campaign.update', $campaign)}}" method="post" >
            @csrf
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Edit Campaign</h4>
                </div>
                <div class="card-body">
                    <div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <label for="name" class="form-label">Name</label> <span class="text-danger">*</span>
                                <input type="text"  id="name"
                                       class="form-control  @error('name') is-invalid @enderror"
                                       value="{{old('name',$campaign->name)}}" name="name"
                                       placeholder="Name">
                                @error('name')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="type" class="form-label">Campaign Type</label> <span class="text-danger">*</span>
                                <select   class="form-select @error('type') is-invalid @enderror"
                                        id="type" name="type">
                                    <option value="sms" @if($campaign->type == 'sms') selected  @endif >SMS</option>
                                    <option value="mms" @if($campaign->type == 'mms') selected @endif>MMS</option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        </div>
                        <div class="row mt-3 " id="campaign_mms">
                            <div class="col-12">
                                <label for="mms" class="form-label">Campaign Image for MMS</label>
                                <input class="form-control" name="mms" type="file" id="mms">
                                @error('mms')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                                <img src="{{asset('campaign/'.$campaign->mms)}}" width="100" class="mt-2  img-thumbnail" alt="" srcset="">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="body" class="form-label">Campaign Body</label> <span class="text-danger">*</span>
                                <textarea id="body" class="form-control  @error('body') is-invalid @enderror" name="body"
                                          rows="10"   placeholder="Campaign Body"> {{old('body',$campaign->body)}}</textarea>
                                @error('body')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-3" style="text-align:end">
                    <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')

@endpush
