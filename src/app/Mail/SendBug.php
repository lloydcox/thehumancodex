<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBug extends Mailable
{
    use Queueable, SerializesModels;

    public $bug;
    public $image1;
    public $image2;
    public $image3;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bug,$image_1,$image_2,$image_3)
    {
        $this->bug = $bug;
        $this->image1 = $image_1;
        $this->image2 = $image_2;
        $this->image3 = $image_3;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Bug reported : ".$this->bug->title)->markdown('mails.bug_report');
    }
}
