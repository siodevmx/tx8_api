<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class newAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $newAdmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($newAdmin)
    {
        $this->newAdmin = $newAdmin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Bienvenido a TX8')->view('emails.new_admin');
    }
}
