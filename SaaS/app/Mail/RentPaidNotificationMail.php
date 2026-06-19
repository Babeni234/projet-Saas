<?php

namespace App\Mail;

use App\Models\PaiementLoyer;
use App\Models\Locataire;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RentPaidNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $paiement;
    public $locataire;
    public $months;
    public $companyName;
    public $agencyName;
    public $logementRef;

    public function __construct(PaiementLoyer $paiement, Locataire $locataire, array $months)
    {
        $this->paiement = $paiement;
        $this->locataire = $locataire;
        $this->months = $months;
        $this->companyName = $locataire->company ? ($locataire->company->legal_name ?? 'PropertyAI') : 'PropertyAI';
        $this->agencyName = $locataire->agency ? $locataire->agency->name : 'N/A';
        
        // Find logement reference
        $this->logementRef = $locataire->affectations->first()?->logement?->reference ?? 'Logement';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notification de Paiement de Loyer - ' . $this->locataire->nom . ' (' . $this->logementRef . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.rent_paid_notification',
        );
    }
}
