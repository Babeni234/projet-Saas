<?php

namespace App\Mail;

use App\Models\Renouvellement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenewalReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $renouvellement;
    public $subjectTitle = 'Rappel de Signature de Contrat';
    public $companyName;
    public $agencyName;
    public $title;
    public $paragraphs = [];
    public $details = [];

    public function __construct(Renouvellement $renouvellement)
    {
        $this->renouvellement = $renouvellement;
        
        $company = $renouvellement->company;
        $agency = $renouvellement->agency;
        
        $this->companyName = $company ? ($company->legal_name ?? 'PropertyAI') : 'PropertyAI';
        $this->agencyName = $agency ? $agency->name : 'Siège';

        $locataireName = $renouvellement->locataire ? ($renouvellement->locataire->nom) : 'Locataire';
        
        $this->title = 'Bonjour ' . $locataireName . ',';
        $this->paragraphs = [
            "Nous vous rappelons que votre contrat actuel arrive à échéance dans deux jours.",
            "Votre demande de renouvellement ayant été acceptée, nous vous invitons à vous présenter rapidement au sein de votre agence de gestion <strong>{$this->agencyName}</strong> afin de procéder à la signature officielle de votre nouveau bail.",
            "Voici les conditions prévues pour votre nouveau contrat :"
        ];

        $this->details = [
            'Agence' => $this->agencyName,
            'Adresse' => $agency ? $agency->address . ' ' . $agency->city : ($company ? $company->address : 'N/A'),
            'Téléphone' => $agency ? $agency->phone : ($company ? $company->phone : 'N/A'),
            'Nouveau Loyer' => number_format($renouvellement->nouveau_loyer, 2, ',', ' ') . ' €',
            'Cycle de Paiement' => $renouvellement->cycle_paiement,
            'Durée' => $renouvellement->duree
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rappel : Signature de votre nouveau contrat de bail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.renouvellement_generic',
        );
    }
}
