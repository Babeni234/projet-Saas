<?php

namespace App\Mail;

use App\Models\Renouvellement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenouvellementCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $renouvellement;
    public $subjectTitle = 'Nouvelle demande de renouvellement';
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
        
        $this->title = 'Demande de Renouvellement Soumise';
        $this->paragraphs = [
            "Une nouvelle demande de renouvellement de bail a été enregistrée dans le système.",
            "Voici les détails de la demande :"
        ];

        $this->details = [
            'Locataire' => $locataireName,
            'Logement concerné' => $logementRef,
            'Loyer Actuel' => $renouvellement->contrat ? number_format($renouvellement->contrat->loyer, 2, ',', ' ') . ' €' : 'N/A',
            'Nouveau Loyer Proposé' => number_format($renouvellement->nouveau_loyer, 2, ',', ' ') . ' €',
            'Cycle de Paiement' => $renouvellement->cycle_paiement,
            'Durée demandée' => $renouvellement->duree,
            'Frais de Contrat' => number_format($renouvellement->frais_contrat, 2, ',', ' ') . ' €',
            'Description/Demande' => $renouvellement->motif_demande ?: 'Aucun détail fourni.'
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle demande de renouvellement de bail - ' . $this->renouvellement->reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.renouvellement_generic',
        );
    }
}
