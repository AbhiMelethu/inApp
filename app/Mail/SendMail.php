<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Define properties
     */
    /**
     * Get array of data
     */
    public $data = array();
    /**
     * View file
     */
    public $view;
    /**
     * Subject
     */
    public $subject = 'Send Mail';

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        //Assign param
        $this->data = $data;
        $this->assignProperties();
    }

    /**
     * Assign params
     */
    protected function assignProperties()
    {
        $this->subject = $this->data['subject'];
        $this->view = $this->data['view'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->view,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
