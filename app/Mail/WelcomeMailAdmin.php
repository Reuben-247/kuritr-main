<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMailAdmin extends Mailable
{
    use Queueable, SerializesModels;

/**
     * Create a new message instance.
     *
     * @return void
     */
    public $name, $verify_code;
    public function __construct($name, $password)
    { $this->name = $name;
        $this->password = $password;
      
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
        return $this->view('email.welcome-admin')
                    ->subject('Welcome to Kuritr')
                    ->from($address, $name);
    }
}
