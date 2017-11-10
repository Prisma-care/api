<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPasswordResetLink extends Mailable
{
    use Queueable, SerializesModels;

    public $data;


    /**
     * SendPassword constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;

        return $this->from('info@prisma.care')
            ->subject('Maak een nieuw Prisma wachtwoord aan') // Make a new Prisma password
            ->subject('Your Prisma Reset Link')
            ->with($data)
            ->markdown('emails.send-password-reset-link');
    }
}