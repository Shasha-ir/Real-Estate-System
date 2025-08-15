<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PropertyUnderReviewMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function build()
    {
        return $this->subject('Your property is under review')
            ->view('emails.property-under-review');
    }
}
