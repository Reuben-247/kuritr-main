<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DebitMail extends Mailable
{
    use Queueable, SerializesModels;

   /**
     * Create a new message instance.
     *
     * @return void
     */
    public $amount, $balance, $purpose;
    public function __construct($amount, $balance, $purpose)
    {
        $this->amount = $amount;
        $this->balance = $balance;
        $this->purpose = $purpose;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = ' Debit Transaction Notification';
        $address = 'support@kuritr.com';
        $name = 'Kuritr';
        return $this->view('email.debit-notification')
                    ->subject($subject)
                    ->from($address, $name);
    }
}
