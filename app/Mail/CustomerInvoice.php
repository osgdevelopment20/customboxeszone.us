<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $customer_invoice_mail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customer_invoice_mail)
    {
       $this->customer_invoice_mail = $customer_invoice_mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $customer_invoice_mail = $this->customer_invoice_mail;
//        dd($customer_invoice_mail);
        return $this->subject('Your invoice from ' . config('app.name'))
            ->view('mail.invoice-mail',compact('customer_invoice_mail'));
    }
}
