<?php

namespace App\Mail;

use App\Models\Renouvellement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenouvellementApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $renouvellement;
    public $subjectTitle = 'Renouvellement Approuvé';
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
        $logementRef = $renouvellement->contrat && $renouvellement->contrat->logement ? $renouvellement->contrat->logement->reference : 'N/A';
        
        $this->title = 'Félicitations ' . $locataireName . ',';
        $this->paragraphs = [
            "Nous avons le plaisir de vous informer que votre demande de renouvellement de bail a été approuvée par l'administration.",
            "Votre nouveau contrat est en cours de préparation. Vous serez notifié prochainement pour procéder à sa signature.",
            "Voici les conditions validées pour votre nouveau bail :"
        ];

        $this->details = [
            'Logement' => $logementRef,
            'Nouveau Loyer' => number_format($renouvellement->nouveau_loyer, 2, ',', ' ') . ' €',
            'Cycle de Paiement' => $renouvellement->cycle_paiement,
            'Durée du bail' => $renouvellement->duree,
            'Agence de gestion' => $this->agencyName,
            'Téléphone Agence' => $agency ? $agency->phone : ($company ? $company->phone : 'N/A')
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre demande de renouvellement a été approuvée - ' . $this->companyName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.renouvellement_generic',
        );
    }
}
