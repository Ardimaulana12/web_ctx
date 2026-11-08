<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactInquiry extends Mailable
{
    public function __construct(public array $inquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->inquiry['email'], $this->inquiry['name'])],
            subject: 'New Contact us inquiry ? CTX Corp',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-inquiry');
    }
}
