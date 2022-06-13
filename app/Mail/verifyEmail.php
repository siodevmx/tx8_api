<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class verifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $userCustomer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userCustomer)
    {
        $this->userCustomer = $userCustomer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verfica tu correo electrónico')->markdown('emails.verify_email');

    }
}
