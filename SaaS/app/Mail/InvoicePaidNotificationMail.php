<?php

namespace App\Mail;

use App\Models\Facture;
use App\Models\Locataire;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoicePaidNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $locataire;
    public $companyName;
    public $agencyName;
    public $logementRef;

    public function __construct(Facture $invoice, Locataire $locataire)
    {
        $this->invoice = $invoice;
        $this->locataire = $locataire;
        $this->companyName = $locataire->company ? ($locataire->company->legal_name ?? 'PropertyAI') : 'PropertyAI';
        $this->agencyName = $locataire->agency ? $locataire->agency->name : 'N/A';
        $this->logementRef = $locataire->affectations->first()?->logement?->reference ?? 'Logement';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notification de Paiement de Facture - ' . $this->locataire->nom . ' (' . $this->logementRef . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice_paid_notification',
        );
    }
}
