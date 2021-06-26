<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LastLoginMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email, $name;
    public function __construct($email, $name)
    { $this->email = $email;
        $this->name = $name;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   $address = 'support@kuritr.com';
        $name = 'Kuritr';
        return $this->view('email.lastloginreminder')
                          ->subject($title)
                          ->from($address, $name);
    }
}
