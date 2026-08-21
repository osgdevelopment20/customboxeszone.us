<div class="row">
    <div class="col-lg-12 mb-35">
        <div class="card my-10">
            <div class="card-header">
                <span class="text-center" style="font-size: 25px;font-weight: 600;">Get Instant Custom Quote</span>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="saveQuote">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Name </label>
                                <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                       wire:model="name" placeholder="Enter your name" aria-required="true"
                                       @error('name') aria-describedby="name-error" @enderror>
                                @error('name')
                                <div class="text-danger" id="name-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                       wire:model="email" placeholder="Enter your email" aria-required="true"
                                       @error('email') aria-describedby="email-error" @enderror>
                                @error('email')
                                <div class="text-danger" id="email-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone </label>
                                <input type="tel" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                       wire:model="phone" placeholder="Enter your phone number" aria-required="true"
                                       @error('phone') aria-describedby="phone-error" @enderror>
                                @error('phone')
                                <div class="text-danger" id="phone-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col ">
                            <div class="form-group">
                                <label for="length" class="form-label">Length (Left to right)</label>
                                <input wire:model="length" type="text" id="length" class="form-control @error('length') is-invalid @enderror"
                                       name="length" placeholder="Enter length" value="{{old('length')}}"
                                       oninput="validateNumericInput(event)"
                                       @error('length') aria-describedby="length-error" @enderror>
                                @error('length')
                                <div class="text-danger" id="length-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="width" class="form-label">Width (Front to back)</label>
                                <input wire:model="width" type="text" id="width" class="form-control @error('width') is-invalid @enderror"
                                       name="width" placeholder="Enter width" oninput="validateNumericInput(event)"
                                       value="{{old('width')}}" @error('width') aria-describedby="width-error" @enderror>
                                @error('width')
                                <div class="text-danger" id="width-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col ">
                            <div class="form-group">
                                <label for="height" class="form-label">Height (Top to bottom)</label>
                                <input wire:model="height" type="text" id="height" class="form-control @error('height') is-invalid @enderror"
                                       name="height" placeholder="Enter height" value="{{old('height')}}"
                                       oninput="validateNumericInput(event)"
                                       @error('height') aria-describedby="height-error" @enderror>
                                @error('height')
                                <div class="text-danger" id="height-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="quantity" class="form-label">Quantity 1 <span class="text-danger">*</span></label>
                                <input type="text" id="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                       wire:model="quantity" placeholder="Enter quantity" aria-required="true"
                                       @error('quantity') aria-describedby="quantity-error" @enderror>
                                @error('quantity')
                                <div class="text-danger" id="quantity-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="quantity2" class="form-label">Quantity 2</label>
                                <input type="text" id="quantity2" class="form-control @error('quantity2') is-invalid @enderror"
                                       wire:model="quantity2" placeholder="Enter second quantity"
                                       @error('quantity2') aria-describedby="quantity2-error" @enderror>
                                @error('quantity2')
                                <div class="text-danger" id="quantity2-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col ">
                            <div class="form-group">
                                <label for="units" class="form-label">Measurement Units</label>
                                <select wire:model="units" id="units" class="form-control @error('units') is-invalid @enderror"
                                        name="units" @error('units') aria-describedby="units-error" @enderror>
                                    <option value="" selected>Select measurement unit</option>
                                    <option value="inches">inches</option>
                                    <option value="cm">cm</option>
                                    <option value="mm">mm</option>
                                </select>
                                @error('units')
                                <div class="text-danger" id="units-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="art_design" class="form-label" style="font-size: 16px;font-weight: 600;">Upload Your Design/Artwork</label>
                                <input type="file" id="art_design" class="form-control @error('art_design.*') is-invalid @enderror"
                                       wire:model="art_design" multiple
                                       aria-describedby="art_design_help @error('art_design.*') art_design_error @enderror">
                                <div id="art_design_help" class="form-text">You can upload multiple files. Accepted formats: JPG, PNG, PDF.</div>
                                @error('art_design.*')
                                <div class="text-danger" id="art_design_error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="notes" class="form-label">Additional Notes</label>
                                <textarea id="notes" class="form-control @error('notes') is-invalid @enderror"
                                          wire:model="notes" placeholder="Enter any additional information"
                                          @error('notes') aria-describedby="notes-error" @enderror></textarea>
                                @error('notes')
                                <div class="text-danger" id="notes-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col text-center">
                            <button type="submit"
                                    class="button button-add-to-cart mt-3"
                                    wire:loading.attr="disabled"
                                    wire:target="saveQuote"
                                    aria-label="Get Quote - Submit quote request">
                                <span wire:loading.remove wire:target="saveQuote">GET QUOTE</span>
                                <span wire:loading wire:target="saveQuote">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    <span class="visually-hidden">Loading...</span>
                                    Sending...
                                </span>
                            </button>
                        </div>
                        <div class="col text-center">
                            <a href="{{ route('cart.quote') }}"
                               class="button button-add-to-cart mt-3"
                                    wire:loading.attr="disabled"
                                    wire:target="saveQuote"
                                    aria-label="Add to Cart - View your quote cart">
                                <span wire:loading.remove wire:target="saveQuote">Add to Cart</span>
                                <span wire:loading wire:target="saveQuote">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    <span class="visually-hidden">Loading...</span>
                                    Sending...
                                </span>
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
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
