<?php

namespace Domain\API\Authentication\Mail;

use Domain\API\Authentication\Data\RegisterData;
use Domain\Global\Data\EmployeeData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRegister extends Mailable
{
    use Queueable, SerializesModels;

    public RegisterData|EmployeeData $request;
    public string $password;

    public function __construct(
        RegisterData|EmployeeData $request,
        string                    $password,
    ) {
        $this->request = $request;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_GENERAL'),
            replyTo: env('MAIL_GENERAL'),
            subject: 'User Register',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.authentication.register',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
