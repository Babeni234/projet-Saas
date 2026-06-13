<?php

namespace App\Mail;

use App\Models\Renouvellement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenouvellementConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public $renouvellement;
    public $subjectTitle = 'Renouvellement Effectué avec Succès';
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
        
        $this->title = 'Confirmation d\'Activation de Bail';
        $this->paragraphs = [
            "Le renouvellement de contrat de bail pour le locataire <strong>{$locataireName}</strong> a été validé et finalisé avec succès.",
            "Toutes les modifications contractuelles et financières ont été appliquées dans la base de données. Les frais de contrat ont également été enregistrés.",
            "Voici le récapitulatif des nouvelles conditions appliquées :"
        ];

        $this->details = [
            'Locataire' => $locataireName,
            'Logement' => $logementRef,
            'Nouveau Loyer' => number_format($renouvellement->nouveau_loyer, 2, ',', ' ') . ' €',
            'Cycle de Paiement' => $renouvellement->cycle_paiement,
            'Nouvelle Durée du bail' => $renouvellement->duree,
            'Frais de Contrat perçus' => number_format($renouvellement->frais_contrat, 2, ',', ' ') . ' €',
            'Agence responsable' => $this->agencyName,
            'Date d\'activation' => now()->toLocaleDateString('fr_FR')
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation de renouvellement de bail - ' . $this->renouvellement->reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.renouvellement_generic',
        );
    }
}
