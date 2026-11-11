<?php

namespace App\Mail;

use App\Models\VariationQuote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VariationQuoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $variationQuote;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(VariationQuote $variationQuote)
    {
        $this->variationQuote = $variationQuote;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'New Order Request - ' . $this->variationQuote->name,
            replyTo: filter_var($this->variationQuote->email, FILTER_VALIDATE_EMAIL) ? [new Address($this->variationQuote->email)] : [],
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.variation-quote',
            with: [
                'variationQuote' => $this->variationQuote,
                'product' => $this->variationQuote->product,
                'variation' => $this->variationQuote->variation,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
