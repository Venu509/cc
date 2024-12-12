<?php

namespace Domain\API\Authentication\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Password',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.authentication.forgot-password',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
