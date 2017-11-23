<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Invitation extends Mailable
{
    use Queueable, SerializesModels;

    public $data;


    /**
     * Invitation constructor. Create a new message instance.
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
        return $this->from('info@prisma.care', 'Prisma')
            ->subject($data['inviter']. ' nodigde je uit voor Prisma')
            ->with($data)
            ->markdown('emails.invite');
    }
}
