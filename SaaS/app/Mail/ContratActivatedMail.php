<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Contrat;

class ContratActivatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contrat;
    public $companyName;
    public $agencyName;
    public $agencyCity;
    public $logementRef;
    public $loyer;
    public $caution;
    public $duree;

    /**
     * Create a new message instance.
     */
    public function __construct(Contrat $contrat, $duree = '1 an')
    {
        $this->contrat = $contrat;
        $this->companyName = $contrat->company?->legal_name ?? 'PropertyAI';
        $this->agencyName = $contrat->agency?->name ?? 'Siège';
        $this->agencyCity = $contrat->agency?->city ?? ($contrat->logement?->batiment?->ville ?? 'N/A');
        $this->logementRef = $contrat->logement?->reference ?? 'Logement';
        $this->loyer = $contrat->loyer;
        $this->caution = $contrat->caution;
        $this->duree = $duree;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Activation de votre contrat de bail - PropertyAI',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contrat_activated',
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
