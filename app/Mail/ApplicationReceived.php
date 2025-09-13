<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Applicant;

class ApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant;

    public function __construct(Applicant $applicant)
    {
        $this->applicant = $applicant;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Received - ' . $this->applicant->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}