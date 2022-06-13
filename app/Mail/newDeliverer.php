<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class newDeliverer extends Mailable
{
    use Queueable, SerializesModels;

    public $newDeliverer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($newDeliverer)
    {
        $this->newDeliverer = $newDeliverer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bienvenido a TX8')->view('emails.new_deliverer');
    }
}
