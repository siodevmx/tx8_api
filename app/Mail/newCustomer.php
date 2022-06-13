<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class newCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public $newCustomer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($newCustomer)
    {
        $this->newCustomer = $newCustomer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bienvenido a TX8')->markdown('emails.new_customer');

    }
}
