<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $senderName;
    public $senderEmail;
    public $messageBody;
    public $companyName;

    /**
     * Create a new message instance.
     */
    public function __construct($senderName, $senderEmail, $messageBody, $companyName)
    {
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
        $this->messageBody = $messageBody;
        $this->companyName = $companyName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[Property AI] Nouveau message de contact pour " . $this->companyName,
            replyTo: [$this->senderEmail],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.partner_contact',
        );
    }
}
