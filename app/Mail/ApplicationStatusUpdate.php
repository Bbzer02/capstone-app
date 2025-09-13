<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Applicant;

class ApplicationStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant;
    public $status;

    public function __construct(Applicant $applicant, $status)
    {
        $this->applicant = $applicant;
        $this->status = $status;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Status Update - ' . $this->applicant->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-status-update',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}