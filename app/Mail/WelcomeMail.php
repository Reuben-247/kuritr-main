<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

  /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name, $code;
    public function __construct($name, $code)
    { $this->name = $name;
        $this->code = $code;
      
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
        return $this->view('email.welcome')
                    ->subject('Welcome to Kuritr community')
                    ->from($address, $name);
    }
}
