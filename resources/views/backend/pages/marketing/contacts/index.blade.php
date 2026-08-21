@extends('backend.layouts.app')
@section('title')
   Add Contacts
@endsection
@section('contents')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <h4 class="card-title  text-primary">Import Contacts</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('dashboard.marketing-contact.import.contacts')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label for="file" class="form-label">Import Contacts</label>
                                <input class="form-control" name="import_contacts" type="file" id="file">
                                @error('import_contacts')
                                <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="col-6">
                                <a  class="btn btn-primary float-end" role="button" href="/excel-sample/import-contacts-for-campaign.xlsx" download="Import Contacts For Campaign">
                                    Download Sample
                                </a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Import</button>
                    </form>
                </div>
            </div>

            <div class="card widget widget-stats">
                <div class="m-3 border-bottom " style="text-align:end">
                    <a href="{{route('dashboard.marketing-contact.create')}}"
                       class="btn rounded-pill btn-primary waves-effect waves-light mb-2"> <i
                            class="ri-add-fill ri-1x"></i>Add Contact
                    </a>
                </div>
                <div class="card-body">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {!! $dataTable->scripts() !!}

@endpush
