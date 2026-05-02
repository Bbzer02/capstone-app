<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\AdminUser;

class AdminLoginConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $ipAddress;
    public $userAgent;
    public $loginTime;
    public $loginMethod;

    /**
     * Create a new message instance.
     */
    public function __construct(AdminUser $admin, $ipAddress = null, $userAgent = null, $loginMethod = 'Email/Password')
    {
        $this->admin = $admin;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->loginTime = now();
        $this->loginMethod = $loginMethod;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CTU Danao Admin - Login Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-login-confirmation',
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

