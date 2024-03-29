<?php

namespace App\Mail\Competition;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompetitionPublished extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($competition)
    {
        $this->data['competition'] = $competition;
        $this->data['title'] = "Competition Published";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Competition Published Successfully!")->markdown('emails.competition.published')->with('data', $this->data);
    }
}
