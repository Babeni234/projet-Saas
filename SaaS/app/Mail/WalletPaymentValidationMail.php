<?php

namespace App\Mail;

use App\Models\PendingWalletPayment;
use App\Models\Locataire;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WalletPaymentValidationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pendingPayment;
    public $locataire;
    public $companyName;
    public $agencyName;
    public $validationUrl;
    public $paymentDescription;

    public function __construct(PendingWalletPayment $pendingPayment, Locataire $locataire)
    {
        $this->pendingPayment = $pendingPayment;
        $this->locataire = $locataire;
        $this->companyName = $locataire->company ? ($locataire->company->legal_name ?? 'PropertyAI') : 'PropertyAI';
        $this->agencyName = $locataire->agency ? $locataire->agency->name : 'N/A';
        $this->validationUrl = url('/wallet/validate-payment/' . $pendingPayment->token);

        if ($pendingPayment->type === 'loyer') {
            $months = data_get($pendingPayment->data, 'months', []);
            $periodNames = array_map(function($m) {
                // E.g. '2026-06'
                $parts = explode('-', $m['periode']);
                if (count($parts) >= 2) {
                    $monthsList = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
                    return $monthsList[intval($parts[1]) - 1] . ' ' . $parts[0];
                }
                return $m['periode'];
            }, $months);
            $this->paymentDescription = 'Règlement de loyer pour la période : ' . implode(', ', $periodNames);
        } else {
            $invoiceNum = data_get($pendingPayment->data, 'invoice_num', 'N/A');
            $this->paymentDescription = 'Règlement de la facture N° ' . $invoiceNum;
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Autorisation de paiement par Portefeuille (Wallet) requise',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.wallet_payment_validation',
        );
    }
}
