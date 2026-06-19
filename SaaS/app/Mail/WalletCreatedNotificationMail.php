<?php

namespace App\Mail;

use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WalletCreatedNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $wallet;
    public $locataire;
    public $companyName;
    public $agencyName;

    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
        $this->locataire = $wallet->locataire;
        $this->companyName = $this->locataire->company ? ($this->locataire->company->legal_name ?? 'PropertyAI') : 'PropertyAI';
        $this->agencyName = $this->locataire->agency ? $this->locataire->agency->name : 'N/A';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouveau Wallet créé par le locataire - ' . $this->locataire->nom,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.wallet_created_notification',
        );
    }
}
