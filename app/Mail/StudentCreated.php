<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $student; // Define a public property to pass data to the view

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($student)
    {
        $this->student = $student;
    }

    public function build()
    {
        return $this->subject('Student Created')->markdown('emails.student.created');
    }
}
