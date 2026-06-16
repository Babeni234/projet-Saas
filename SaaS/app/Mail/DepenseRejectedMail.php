<?php

namespace App\Mail;

use App\Models\Depense;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepenseRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $depense;
    public $subjectTitle;
    public $companyName;
    public $agencyName;
    public $title;
    public $paragraphs = [];
    public $details = [];

    public function __construct(Depense $depense, string $customMessage)
    {
        $this->depense = $depense;
        $company = $depense->company;
        $agency = $depense->agency;

        $this->companyName = $company ? ($company->legal_name ?? 'PropertyAI') : 'PropertyAI';
        $this->agencyName = $agency ? $agency->name : 'Siège';

        $this->subjectTitle = "Refus de la demande de dépense - " . $depense->reference;
        $this->title = 'Demande de Dépense Refusée';
        $this->paragraphs = [
            "Votre demande de dépense a été refusée par la direction.",
            "**Message de la direction :**",
            $customMessage,
            "Voici les détails de la dépense concernée :"
        ];

        $this->details = [
            'Référence' => $depense->reference,
            'Titre' => $depense->titre,
            'Montant' => number_format($depense->montant, 2, ',', ' ') . ' XAF',
            'Date' => $depense->date_depense ? $depense->date_depense->format('d/m/Y') : 'N/A',
            'Statut' => 'Refusé / Annulé',
        ];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectTitle,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.renouvellement_generic',
        );
    }
}
