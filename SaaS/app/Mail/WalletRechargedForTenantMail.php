<?php

namespace App\Mail;

use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WalletRechargedForTenantMail extends Mailable
{
    use Queueable, SerializesModels;

    public $wallet;
    public $amount;
    public $newBalance;
    public $refTx;
    public $locataire;
    public $companyName;

    public function __construct(Wallet $wallet, $amount, $newBalance, $refTx)
    {
        $this->wallet = $wallet;
        $this->amount = $amount;
        $this->newBalance = $newBalance;
        $this->refTx = $refTx;
        $this->locataire = $wallet->locataire;
        $this->companyName = $this->locataire->company ? ($this->locataire->company->legal_name ?? 'PropertyAI') : 'PropertyAI';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre portefeuille électronique (Wallet) a été alimenté - ' . $this->companyName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.wallet_recharged_for_tenant',
        );
    }
}
