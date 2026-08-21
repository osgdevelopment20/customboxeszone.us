<div>
    <!-- Variation Quote Modal -->
    <div wire:ignore.self class="modal fade" id="variationQuoteModal" tabindex="-1" aria-labelledby="variationQuoteModalLabel" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="variationQuoteModalLabel">Get a Quote</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="submit" novalidate>
                    <div class="modal-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="alert alert-info">
                            <small>Please fill in your details to get a quote for this Standard Sizes & Estimated Prices.</small>
                        </div>

                        <div class="mb-3">
                            <label for="quote_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="quote_name" wire:model.blur="name" placeholder="Enter your name">
                        </div>

                        <div class="mb-3">
                            <label for="quote_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="quote_email" wire:model.blur="email" placeholder="Enter your email">
                        </div>

                        <div class="mb-3">
                            <label for="quote_phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   id="quote_phone" wire:model.blur="phone" placeholder="Enter your phone number">
                        </div>

                        <div class="mb-3">
                            <label for="quote_quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                   id="quote_quantity" wire:model.blur="quantity" min="1" placeholder="Enter quantity">
                        </div>

                        <input type="hidden" wire:model="variation_id" id="variation_id_livewire">
                        @error('variation_id')
                            <div class="text-danger small d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>Submit Quote Request</span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Submitting...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Fix modal z-index and backdrop issues */
    #variationQuoteModal {
        z-index: 9999 !important;
    }

    .modal-backdrop {
        z-index: 9998 !important;
    }

    /* Ensure modal appears on top */
    #variationQuoteModal.show {
        display: block !important;
        z-index: 9999 !important;
    }

    /* Fix modal positioning */
    #variationQuoteModal .modal-dialog {
        z-index: 10000 !important;
    }

    /* Validation error styling */
    #variationQuoteModal .form-control.is-invalid {
        border-color: #dc3545 !important;
        border-width: 2px !important;
        box-shadow: none !important;
    }

    #variationQuoteModal .form-control.is-invalid:focus {
        border-color: #dc3545 !important;
        border-width: 2px !important;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
    }

    #variationQuoteModal .text-danger.small {
        display: block !important;
        color: #dc3545 !important;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    }
</style>
@endpush

@push('scripts')
<script>
    window.addEventListener('quote-submitted', event => {
        setTimeout(() => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('variationQuoteModal'));
            if (modal) {
                modal.hide();
            }
        }, 2000);
    });

    // Ensure modal backdrop is properly positioned
    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('variationQuoteModal');
        if (modalElement) {
            modalElement.addEventListener('show.bs.modal', function () {
                // Move modal to body if it's nested
                if (this.parentElement.tagName !== 'BODY') {
                    document.body.appendChild(this);
                }
            });
        }
    });
</script>
@endpush
