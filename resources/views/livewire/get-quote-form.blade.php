<div>
<div class="row" id="getQuoteForm">
    <div class="col-lg-12 mt-35 mb-35" >
        <div class="card my-10">
            <div class="card-header">
                <span class="text-center" style="font-size: 25px; font-weight: 600;">Get Instant Custom Quote</span>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="saveQuote" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4  col-sm-6">
                            <div class="form-group">
                                <label for="name-quote" class="form-label">Name</label>
                                <input wire:model="name" type="text" id="name-quote" class="form-control @error('name') is-invalid @enderror"
                                       name="name" placeholder="Enter your name" value="{{old('name')}}" aria-required="true"
                                       @error('name') aria-describedby="name-error" @enderror>
                                @error('name')
                               <div class="text-danger" id="name-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4  col-sm-6">
                            <div class="form-group">
                                <label for="email-quote" class="form-label">Email <span class="text-danger">*</span></label>
                                <input wire:model="email" type="email" id="email-quote" class="form-control @error('email') is-invalid @enderror"
                                       name="email" placeholder="Enter your email" value="{{old('email')}}" aria-required="true"
                                       @error('email') aria-describedby="email-error" @enderror>
                                @error('email')
                               <div class="text-danger" id="email-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4  col-sm-12">
                            <div class="form-group">
                                <label for="phone-quote" class="form-label">Phone</label>
                                <input wire:model="phone" type="tel" id="phone-quote" class="form-control @error('phone') is-invalid @enderror"
                                       name="phone" placeholder="Enter your phone number" value="{{old('phone')}}" aria-required="true"
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
                                <label for="quantity-quote" class="form-label">Quantity 1 <span class="text-danger">*</span></label>
                                <input wire:model="quantity" type="text" id="quantity-quote" class="form-control @error('quantity') is-invalid @enderror"
                                       name="quantity" placeholder="Enter quantity" value="{{old('quantity')}}" aria-required="true"
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
                                <label for="quantity2-quote" class="form-label">Quantity 2</label>
                                <input wire:model="quantity2" type="text" id="quantity2-quote" class="form-control @error('quantity2') is-invalid @enderror"
                                       name="quantity2" placeholder="Enter second quantity" value="{{old('quantity2')}}"
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
                                <label for="quantity3" class="form-label">Quantity 3</label>
                                <input wire:model="quantity3" type="text" id="quantity3" class="form-control @error('quantity3') is-invalid @enderror"
                                       name="quantity3" placeholder="Enter third quantity" value="{{old('quantity3')}}"
                                       @error('quantity3') aria-describedby="quantity3-error" @enderror>
                                @error('quantity3')
                               <div class="text-danger" id="quantity3-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col ">
                            <div class="form-group">
                                <label for="colors" class="form-label">Printing Options</label>
                                <select wire:model="colors" id="colors" class="form-control @error('colors') is-invalid @enderror"
                                        name="colors" @error('colors') aria-describedby="colors-error" @enderror>
                                    <option value="">Select printing option</option>
                                    <option value="1 Color Printing">1 Color Printing</option>
                                    <option value="2 Color Printing">2 Color Printing</option>
                                    <option value="3 Color Printing">3 Color Printing</option>
                                    <option value="4 Color Printing">4 Color Printing</option>
                                    <option value="4/1 Color Printing">4/1 Color Printing</option>
                                    <option value="4/2 Color Printing">4/2 Color Printing</option>
                                    <option value="4/3 Color Printing">4/3 Color Printing</option>
                                    <option value="4/4 Color Printing">4/4 Color Printing</option>
                                    <option value="Full Color Printing">Full Color Printing</option>
                                    <option value="Need Consultation">Need Consultation</option>
                                    <option value="No Printing">No Printing</option>
                                </select>
                                @error('colors')
                               <div class="text-danger" id="colors-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col ">
                            <div class="form-group">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input wire:model="product_name" type="text" id="product_name" class="form-control @error('product_name') is-invalid @enderror"
                                       name="product_name" placeholder="Enter product name" value="{{old('product_name')}}"
                                       @error('product_name') aria-describedby="product_name-error" @enderror>
                                @error('product_name')
                               <div class="text-danger" id="product_name-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col ">
                            <div class="form-group">
                                <label for="box_style" class="form-label">Box Style</label>
                                <select wire:model="box_style" id="box_style" class="form-control @error('box_style') is-invalid @enderror"
                                        name="box_style" @error('box_style') aria-describedby="box_style-error" @enderror>
                                    <option value="">Select box style</option>
                                    <option value="Straight Tuck Box">Straight Tuck Box</option>
                                    <option value="Reverse Tuck Box">Reverse Tuck Box</option>
                                    <option value="Auto Lock Tuck Top">Auto Lock Tuck Top</option>
                                    <option value="Seal-End Box">Seal-End Box</option>
                                    <option value="Mailer Box">Mailer Box</option>
                                    <option value="Pillow Box">Pillow Box</option>
                                    <option value="Sleeve Box">Sleeve Box</option>
                                    <option value="Display Box">Display Box</option>
                                    <option value="Dispenser Box">Dispenser Box</option>
                                    <option value="Gable Box">Gable Box</option>
                                    <option value="Hang Tab Box">Hang Tab Box</option>
                                    <option value="Hexagon Box">Hexagon Box</option>
                                    <option value="Pyramid Box">Pyramid Box</option>
                                </select>
                                @error('box_style')
                               <div class="text-danger" id="box_style-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">
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

                        <div class="col">
                            <div class="form-group">
                                <label for="stock" class="form-label">Material Stock</label>
                                <select wire:model="stock" id="stock" class="form-control @error('stock') is-invalid @enderror"
                                        name="stock" @error('stock') aria-describedby="stock-error" @enderror>
                                    <option value="">Select material stock</option>
                                    <option value="12pt Cardboard Stock">12pt Cardboard Stock</option>
                                    <option value="14pt Cardboard Stock">14pt Cardboard Stock</option>
                                    <option value="16pt Cardboard Stock">16pt Cardboard Stock</option>
                                    <option value="18pt Cardboard Stock">18pt Cardboard Stock</option>
                                    <option value="20pt Cardboard Stock">20pt Cardboard Stock</option>
                                    <option value="22pt Cardboard Stock">22pt Cardboard Stock</option>
                                    <option value="24pt Cardboard Stock">24pt Cardboard Stock</option>
                                    <option value="Kraft Stock">Kraft Stock</option>
                                    <option value="Corrugated Stock">Corrugated Stock</option>
                                    <option value="Rigid Stock">Rigid Stock</option>
                                    <option value="Other">Other</option>
                                </select>
                                @error('stock')
                               <div class="text-danger" id="stock-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <fieldset>
                                    <legend class="mb-3" style="font-size: 16px;font-weight: 600;">Additional Options</legend>
                                    <div class="row">
                                        <div class="custome-checkbox col">
                                            <input type="checkbox" class="form-check-input" id="option_spot_uv" wire:model="other_products" name="other_products[]" value="Spot UV">
                                            <label class="form-check-label" for="option_spot_uv">Spot UV</label>
                                        </div>
                                        <div class="custome-checkbox col">
                                            <input type="checkbox" class="form-check-input" id="option_foil_stamp" wire:model="other_products" name="other_products[]" value="Foil Stamp">
                                            <label class="form-check-label" for="option_foil_stamp">Foil Stamp</label>
                                        </div>
                                        <div class="custome-checkbox col">
                                            <input type="checkbox" class="form-check-input" id="option_emboss" value="Emboss" wire:model="other_products" name="other_products[]">
                                            <label class="form-check-label" for="option_emboss">Emboss</label>
                                        </div>
                                        <div class="custome-checkbox col">
                                            <input type="checkbox" class="form-check-input" id="option_deboss" value="Deboss" wire:model="other_products" name="other_products[]">
                                            <label class="form-check-label" for="option_deboss">Deboss</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="custome-checkbox col">
                                            <input type="checkbox" class="form-check-input" id="option_pvc_window" value="PVC Window" wire:model="other_products" name="other_products[]">
                                            <label class="form-check-label" for="option_pvc_window">PVC Window</label>
                                        </div>
                                        <div class="custome-checkbox col">
                                            <input type="checkbox" class="form-check-input" id="option_window_cut_out" value="Window Cut Out" wire:model="other_products" name="other_products[]">
                                            <label class="form-check-label" for="option_window_cut_out">Window Cut Out</label>
                                        </div>
                                        <div class="custome-checkbox col">
                                            <input type="checkbox" class="form-check-input" id="option_inserts" value="Inserts" wire:model="other_products" name="other_products[]">
                                            <label class="form-check-label" for="option_inserts">Inserts</label>
                                        </div>
                                        <div class="custome-checkbox col">
                                            <input type="checkbox" class="form-check-input" id="option_consultation" value="Need Consultation" wire:model="other_products" name="other_products[]">
                                            <label class="form-check-label" for="option_consultation">Need Consultation</label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="row">
    <div class="col">
        <div class="form-group">
            <label for="art_design-quote" class="form-label" style="font-size: 16px;font-weight: 600;">Upload Your Design/Artwork</label>
            <input type="file" multiple
                   wire:model="art_design"
                   class="form-control @error('art_design') is-invalid @enderror @error('art_design.*') is-invalid @enderror"
                   id="art_design-quote"
                   name="art_design[]"
                   accept=".jpeg,.jpg,.png,.gif,.pdf,.ai,.psd,.cdr,.webp,.svg"
                   aria-describedby="art_design_help @error('art_design') art_design_error @enderror @error('art_design.*') art_design_file_error @enderror">

            <div id="art_design_help" class="mt-2 small">
                Allowed types: JPEG, PNG, PDF, AI, PSD, CDR, WEBP, SVG
            </div>

            <!-- Error Messages -->
            @error('art_design')
            <div class="invalid-feedback" id="art_design_error">
                {{ $message }}
            </div>
            @enderror

            @error('art_design.*')
            <div class="invalid-feedback" id="art_design_file_error">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
</div>
                    <div class="row">
                        <div class="col ">
                            <div class="form-group">
                                <label for="note-quote" class="form-label">Additional Notes</label>
                                <textarea id="note-quote" class="form-control @error('notes') is-invalid @enderror"
                                          wire:model="notes" name="notes" placeholder="Enter any additional information"
                                          @error('notes') aria-describedby="notes-error" @enderror></textarea>
                                @error('notes')
                               <div class="text-danger" id="notes-error">
                                    {{$message}}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Hidden field to track referrer page -->
                    <input type="hidden" id="referrer_page" wire:model="referrer_page" name="referrer_page" value="">
                    
                    {{-- <div class="g-recaptcha" data-sitekey="6Lf6o4EaAAAAAOtCKcbQBX1JuWixNMp_lonoEyAw"></div> --}}
                    <div class="row">
    <div class="col text-center">
        <button type="submit"
                class="button button-add-to-cart mt-3"
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
</div>
                    <script>
                        function validateNumericInput(event) {
                            const input = event.target;
                            // Remove any non-numeric characters except decimal point
                            let value = input.value.replace(/[^\d.]/g, '');

                            // Ensure only one decimal point
                            const parts = value.split('.');
                            if (parts.length > 2) {
                                value = parts[0] + '.' + parts.slice(1).join('');
                            }

                            input.value = value;
                        }
                    </script>
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

</div>
