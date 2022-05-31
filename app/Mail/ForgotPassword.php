<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $empData;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($userData)
    {
        $this->user_data = $userData;
    }



    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        return $this->from('XEAMHR@xeamventures.com')
                    ->subject('Forgot Password')
                    ->view('emails.forgotPassword')
                    ->with([
                        'user_data' => $this->user_data
                    ]);
    }

}

