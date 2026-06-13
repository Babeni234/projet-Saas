<?php

namespace App\Mail;

use App\Models\Renouvellement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenouvellementDeleted extends Mailable
{
    use Queueable, SerializesModels;

    public $renouvellement;
    public $subjectTitle = 'Demande de Renouvellement Supprimée';
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
        
        $this->title = 'Avertissement de Suppression';
        $this->paragraphs = [
            "Nous vous informons qu'une demande de renouvellement précédemment refusée a été définitivement supprimée par l'agence de gestion <strong>{$this->agencyName}</strong>.",
            "Voici les détails de la demande supprimée :"
        ];

        $this->details = [
            'Locataire' => $locataireName,
            'Référence de la demande' => $renouvellement->reference,
            'Agence' => $this->agencyName,
            'Loyer Proposé' => number_format($renouvellement->nouveau_loyer, 2, ',', ' ') . ' €',
            'Date de Suppression' => now()->toLocaleDateString('fr_FR')
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Suppression d\'une demande de renouvellement - ' . $this->renouvellement->reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.renouvellement_generic',
        );
    }
}
