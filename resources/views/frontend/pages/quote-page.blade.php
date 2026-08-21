@extends('frontend.layouts.app')
@section('title')
Thank You
@endsection
@push('styles')

@endpush
@section('content')

    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <livewire:get-quote-form />
                </div>
            </div>
        </div>
    </section>

@endsection
