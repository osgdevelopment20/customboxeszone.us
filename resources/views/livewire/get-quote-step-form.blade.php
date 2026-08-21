<div class="card my-10">
    <div class="card-header">
        <span class="text-center" style="font-size: 20px; font-weight: 600;">Get Instant Quote</span>
    </div>
    <div class="card-body">
        <form wire:submit.prevent="saveQuote" id="demo-form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <!--<label for="name-home" class="form-label">Name</label>-->
                        <input type="text" wire:model="name" id="name-home" class="form-control @error('name') is-invalid @enderror"
                               name="name" placeholder="Enter your name" value="{{old('name')}}" aria-required="true"
                               @error('name') aria-describedby="name-error" @enderror>
                        @error('name')
                       <div class="text-danger" id="name-error">
                                    {{$message}}
                                </div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <!--<label for="email-home" class="form-label">Email <span class="text-danger">*</span></label>-->
                        <input type="email" wire:model="email" id="email-home" class="form-control @error('email') is-invalid @enderror"
                               name="email" placeholder="Enter your email" value="{{old('email')}}" aria-required="true"
                               @error('email') aria-describedby="email-error" @enderror>
                        @error('email')
                        <div class="text-danger" id="email-error">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <!--<label for="phone-home" class="form-label">Phone</label>-->
                        <input type="tel" wire:model="phone" id="phone-home" class="form-control @error('phone') is-invalid @enderror"
                               name="phone" placeholder="Enter your phone number" value="{{old('phone')}}"
                               @error('phone') aria-describedby="phone-error" @enderror>
                        @error('phone')
                        <div class="text-danger" id="phone-error">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <!--<label for="quantity-home" class="form-label">Quantity</label>-->
                        <input type="text" wire:model="quantity" id="quantity-home" class="form-control @error('quantity') is-invalid @enderror"
                               name="quantity" placeholder="Enter quantity" value="{{old('quantity')}}" aria-required="true"
                               @error('quantity') aria-describedby="quantity-error" @enderror>
                        @error('quantity')
                        <div class="text-danger" id="quantity-error">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 ">
                    <div class="form-group">
                        <!--<label for="notes-home" class="form-label">Additional Notes</label>-->
                        <textarea wire:model="notes" id="notes-home" class="form-control @error('notes') is-invalid @enderror"
                                  name="notes" placeholder="Enter any additional information"
                                  @error('notes') aria-describedby="notes-error" @enderror></textarea>
                        @error('notes')
                        <div class="text-danger" id="notes-error">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                </div>
            </div>

            {{--    <div class="g-recaptcha" data-sitekey="6Lf6o4EaAAAAAOtCKcbQBX1JuWixNMp_lonoEyAw"></div>--}}
<div class="col text-center">
    <button type="button"
            class="button button-add-to-cart mt-3"
            onclick="window.location='{{ route('get.quotes') }}'"
            wire:loading.attr="disabled"
            wire:target="saveQuote"
            aria-label="Submit quote request">
        <span wire:loading.remove wire:target="saveQuote">GET QUOTE</span>
        <span wire:loading wire:target="saveQuote">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span class="visually-hidden">Loading...</span>
            Sending...
        </span>
    </button>
</div>


        </form>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Using ipinfo.io
            fetch('https://ipinfo.io/json?token=a31372f75ab60a')
                .then(response => response.json())
                .then(data => {
                    console.log(data,'data');
                    // Emit the data to the Livewire component
                    Livewire.emit('locationDetected', {
                        ip: data.ip,
                        city: data.city,
                        region: data.region,
                        country: data.country,
                        location: data.loc,
                        timezone: data.timezone,
                        postal: data.postal
                    });
                })
                .catch(error => console.error('Error fetching location:', error));
        });
    </script>
</div>
