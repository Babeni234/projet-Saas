<?php

namespace App\Mail;

use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WalletCreatedForTenantMail extends Mailable
{
    use Queueable, SerializesModels;

    public $wallet;
    public $password;
    public $locataire;
    public $companyName;
    public $agencyName;

    public function __construct(Wallet $wallet, $password)
    {
        $this->wallet = $wallet;
        $this->password = $password;
        $this->locataire = $wallet->locataire;
        $this->companyName = $this->locataire->company ? ($this->locataire->company->legal_name ?? 'PropertyAI') : 'PropertyAI';
        $this->agencyName = $this->locataire->agency ? $this->locataire->agency->name : 'N/A';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre portefeuille électronique (Wallet) a été activé - ' . $this->companyName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.wallet_created_for_tenant',
        );
    }
}
