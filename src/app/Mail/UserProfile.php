<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Domain\API\Authentication\Data\RegisterData;

class UserProfile extends Mailable
{
    use Queueable, SerializesModels;

    public RegisterData $request;

    public function __construct(
        RegisterData $request,
    ) {
        $this->request = $request;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your App Credentials',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.authentication.user-profile',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
