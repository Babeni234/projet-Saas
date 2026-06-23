<?php

namespace App\Mail;

use App\Models\Contrat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaseExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contrat;
    public $companyName;
    public $agencyName;
    public $locataireName;
    public $logementRef;
    public $dateFin;

    /**
     * Create a new message instance.
     */
    public function __construct(Contrat $contrat)
    {
        $this->contrat = $contrat;
        $this->companyName = $contrat->company?->legal_name ?? 'PropertyAI';
        $this->agencyName = $contrat->agency?->name ?? 'Siège';
        $this->locataireName = $contrat->locataire?->nom ?? 'Locataire';
        $this->logementRef = $contrat->logement?->reference ?? 'Logement';
        $this->dateFin = $contrat->fin ? $contrat->fin->toDateString() : now()->toDateString();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Expiration de votre contrat de bail - {$this->companyName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.lease_expired',
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
