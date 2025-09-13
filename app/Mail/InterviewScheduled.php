<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Interview;

class InterviewScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public $interview;

    public function __construct(Interview $interview)
    {
        $this->interview = $interview;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Interview Scheduled - ' . $this->interview->applicant->job->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.interview-scheduled',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}