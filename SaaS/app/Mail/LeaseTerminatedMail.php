<?php

namespace App\Mail;

use App\Models\Affectation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaseTerminatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $affectation;
    public $companyName;
    public $agencyName;
    public $locataireName;
    public $logementRef;
    public $dateFin;

    /**
     * Create a new message instance.
     */
    public function __construct(Affectation $affectation)
    {
        $this->affectation = $affectation;
        $this->companyName = $affectation->company?->legal_name ?? 'PropertyAI';
        $this->agencyName = $affectation->agency?->name ?? 'Siège';
        $this->locataireName = $affectation->locataire?->nom ?? 'Locataire';
        $this->logementRef = $affectation->logement?->reference ?? 'Logement';
        $this->dateFin = $affectation->date_fin ? $affectation->date_fin->toDateString() : now()->toDateString();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Résiliation de votre contrat de bail - {$this->companyName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.lease_terminated',
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
