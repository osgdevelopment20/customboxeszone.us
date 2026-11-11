<div class="cbz-order-form">
    <div class="cbz-card">
        <div class="cbz-card__head">
            <h2 class="cbz-card__title">Get an Instant Custom Quote</h2>
            <p class="cbz-card__subtitle">Tell us your size and quantity — we'll price it for you, free of charge. No payment is collected from this form.</p>
        </div>

        <div class="cbz-card__body">
            @if (session()->has('error'))
                <div class="cbz-alert cbz-alert--error" role="alert">
                    <i class="fi-rs-cross-circle" aria-hidden="true"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form wire:submit.prevent="getInstantQuote" novalidate>
                <div class="cbz-field-grid cbz-field-grid--3">
                    <div class="cbz-field">
                        <label for="cbz-name" class="cbz-label">Name</label>
                        <input type="text" id="cbz-name" wire:model.defer="name" value="{{ $name }}" autocomplete="name"
                               class="cbz-input @error('name') is-invalid @enderror" placeholder="John Smith">
                        @error('name') <span class="cbz-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="cbz-field">
                        <label for="cbz-email" class="cbz-label">Email <span class="cbz-req">*</span></label>
                        <input type="email" id="cbz-email" wire:model.defer="email" value="{{ $email }}" autocomplete="email"
                               class="cbz-input @error('email') is-invalid @enderror" placeholder="you@company.com">
                        @error('email') <span class="cbz-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="cbz-field">
                        <label for="cbz-phone" class="cbz-label">Phone</label>
                        <input type="tel" id="cbz-phone" wire:model.defer="phone" value="{{ $phone }}" autocomplete="tel"
                               class="cbz-input @error('phone') is-invalid @enderror" placeholder="+1 (000) 000-0000">
                        @error('phone') <span class="cbz-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <fieldset class="cbz-fieldset">
                    <legend class="cbz-legend">
                        Box Dimensions
                        <span class="cbz-legend__hint">Leave blank if you're not sure — we'll help you choose.</span>
                    </legend>

                    <div class="cbz-field-grid cbz-field-grid--4">
                        <div class="cbz-field">
                            <label for="cbz-length" class="cbz-label">Length <span class="cbz-label__note">(L to R)</span></label>
                            <input type="text" inputmode="decimal" id="cbz-length" wire:model.defer="length" value="{{ $length }}"
                                   class="cbz-input @error('length') is-invalid @enderror" placeholder="0.00">
                            @error('length') <span class="cbz-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="cbz-field">
                            <label for="cbz-width" class="cbz-label">Width <span class="cbz-label__note">(F to B)</span></label>
                            <input type="text" inputmode="decimal" id="cbz-width" wire:model.defer="width" value="{{ $width }}"
                                   class="cbz-input @error('width') is-invalid @enderror" placeholder="0.00">
                            @error('width') <span class="cbz-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="cbz-field">
                            <label for="cbz-height" class="cbz-label">Height <span class="cbz-label__note">(T to B)</span></label>
                            <input type="text" inputmode="decimal" id="cbz-height" wire:model.defer="height" value="{{ $height }}"
                                   class="cbz-input @error('height') is-invalid @enderror" placeholder="0.00">
                            @error('height') <span class="cbz-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="cbz-field">
                            <label for="cbz-units" class="cbz-label">Units</label>
                            <select id="cbz-units" wire:model.defer="units" class="cbz-input cbz-select @error('units') is-invalid @enderror">
                                <option value="inches" @selected($units === 'inches')>Inches</option>
                                <option value="cm" @selected($units === 'cm')>Centimetres</option>
                                <option value="mm" @selected($units === 'mm')>Millimetres</option>
                            </select>
                            @error('units') <span class="cbz-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </fieldset>

                <div class="cbz-field-grid cbz-field-grid--2">
                    <div class="cbz-field">
                        <label for="cbz-quantity" class="cbz-label">Quantity <span class="cbz-req">*</span></label>
                        <div class="cbz-stepper">
                            <button type="button" class="cbz-stepper__btn" aria-label="Decrease quantity"
                                    onclick="cbzStep('cbz-quantity', -50)">&minus;</button>
                            <input type="number" min="1" id="cbz-quantity" wire:model.defer="quantity" value="{{ $quantity }}"
                                   class="cbz-input cbz-stepper__input @error('quantity') is-invalid @enderror" placeholder="100">
                            <button type="button" class="cbz-stepper__btn" aria-label="Increase quantity"
                                    onclick="cbzStep('cbz-quantity', 50)">+</button>
                        </div>
                        @error('quantity') <span class="cbz-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="cbz-field">
                        <label for="cbz-artwork" class="cbz-label">Design / Artwork</label>
                        <label for="cbz-artwork" class="cbz-upload @error('artwork.*') is-invalid @enderror">
                            <i class="fi-rs-cloud-upload" aria-hidden="true"></i>
                            <span class="cbz-upload__text">
                                <strong>Choose files</strong> or drag them here
                                <small>JPG, PNG, PDF, AI, PSD, CDR, SVG — up to 10 files</small>
                            </span>
                            <input type="file" id="cbz-artwork" wire:model="artwork" multiple class="cbz-upload__input"
                                   accept=".jpg,.jpeg,.png,.gif,.webp,.svg,.pdf,.ai,.psd,.cdr">
                        </label>

                        <div wire:loading wire:target="artwork" class="cbz-upload__status">
                            <span class="cbz-spinner" aria-hidden="true"></span> Uploading…
                        </div>

                        @if (!empty($artwork))
                            <ul class="cbz-filelist" wire:loading.remove wire:target="artwork">
                                @foreach ($artwork as $file)
                                    <li><i class="fi-rs-file" aria-hidden="true"></i> {{ $file->getClientOriginalName() }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @error('artwork') <span class="cbz-error">{{ $message }}</span> @enderror
                        @error('artwork.*') <span class="cbz-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="cbz-actions">
                    <button type="submit" class="cbz-btn cbz-btn--primary"
                            wire:loading.attr="disabled" wire:target="getInstantQuote,addToCart">
                        <span wire:loading.remove wire:target="getInstantQuote">
                            <i class="fi-rs-paper-plane" aria-hidden="true"></i> Get Instant Quote
                        </span>
                        <span wire:loading wire:target="getInstantQuote">
                            <span class="cbz-spinner" aria-hidden="true"></span> Submitting…
                        </span>
                    </button>

                    <button type="button" wire:click="addToCart" class="cbz-btn cbz-btn--outline"
                            wire:loading.attr="disabled" wire:target="getInstantQuote,addToCart">
                        <span wire:loading.remove wire:target="addToCart">
                            <i class="fi-rs-shopping-cart-add" aria-hidden="true"></i> Add to Cart
                        </span>
                        <span wire:loading wire:target="addToCart">
                            <span class="cbz-spinner" aria-hidden="true"></span> Adding…
                        </span>
                    </button>
                </div>

                <ul class="cbz-trust">
                    <li><i class="fi-rs-check" aria-hidden="true"></i> Free design support</li>
                    <li><i class="fi-rs-check" aria-hidden="true"></i> No die &amp; plate charges</li>
                    <li><i class="fi-rs-check" aria-hidden="true"></i> Response within 24 hours</li>
                    <li><i class="fi-rs-check" aria-hidden="true"></i> Final price, shipping, and production time confirmed before payment</li>
                </ul>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function cbzStep(inputId, step) {
            var input = document.getElementById(inputId);
            if (!input) return;
            var next = (parseInt(input.value, 10) || 0) + step;
            input.value = next < 1 ? 1 : next;
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }

        document.addEventListener('DOMContentLoaded', function () {
            fetch('https://ipinfo.io/json?token=a31372f75ab60a')
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (window.Livewire) {
                        window.Livewire.emit('locationDetected', {
                            ip: data.ip,
                            city: data.city,
                            region: data.region,
                            country: data.country,
                            postal: data.postal
                        });
                    }
                })
                .catch(function () { /* location is optional */ });
        });
    </script>
@endpush
