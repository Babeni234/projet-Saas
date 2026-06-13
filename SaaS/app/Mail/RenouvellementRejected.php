<?php

namespace App\Mail;

use App\Models\Renouvellement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenouvellementRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $renouvellement;
    public $subjectTitle = 'Demande de Renouvellement Refusée';
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
            "Nous regrettons de vous informer que votre demande de renouvellement de bail n'a pas pu être acceptée dans ses conditions actuelles.",
            "Voici les détails concernant cette décision :",
            "<strong style='color: #ef4444;'>Motif du refus :</strong><br>" . nl2br(e($renouvellement->motif_rejet)),
            "N'hésitez pas à vous rapprocher de votre agence de gestion pour discuter de solutions alternatives ou reformuler une proposition conforme."
        ];

        $this->details = [
            'Réf. demande' => $renouvellement->reference,
            'Agence de gestion' => $this->agencyName,
            'Téléphone Agence' => $agency ? $agency->phone : ($company ? $company->phone : 'N/A')
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mise à jour concernant votre demande de renouvellement - ' . $this->companyName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.renouvellement_generic',
        );
    }
}
