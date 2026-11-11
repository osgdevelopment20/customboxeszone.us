<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PriceMatchMail extends Mailable
{
    use Queueable, SerializesModels;

    public $price_match;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($price_match)
    {
      $this->price_match = $price_match;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $price_match = $this->price_match;
       $info= json_decode($price_match->info);
         
        //   $price_match = \Session::get('price');
        //   $info= json_decode($price_match->info);
          
        
        if (filter_var($price_match->email, FILTER_VALIDATE_EMAIL)) {
            $this->replyTo($price_match->email);
        }

        return $this->subject('New Price Match Request - ' . ($price_match->name ?: $price_match->email))
            ->view('mail.price-match-mail',compact('price_match','info'));
    }
}
