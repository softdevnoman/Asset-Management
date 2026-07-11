<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeOrganizationMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Account $account;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Account $account)
    {
        $this->user = $user;
        $this->account = $account;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . config('app.name', 'EQTRAK') . ' - Verify Your Organization',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome_organization',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
