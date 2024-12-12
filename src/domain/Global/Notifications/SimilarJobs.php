<?php

namespace Domain\Global\Notifications;

use Domain\Vacancy\Models\Vacancy;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SimilarJobs extends Mailable
{
    use Queueable, SerializesModels;

    public Vacancy $vacancy;

    public function __construct(
        Vacancy $vacancy
    )
    {
        $this->vacancy = $vacancy;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: env('MAIL_GENERAL'),
            replyTo: env('MAIL_GENERAL'),
            subject: 'Similar Jobs',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.notifications.similar-jobs',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
