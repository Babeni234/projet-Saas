<?php

namespace App\Mail;

use App\Models\Depense;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DepenseCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $depense;
    public $subjectTitle;
    public $companyName;
    public $agencyName;
    public $title;
    public $paragraphs = [];
    public $details = [];

    public function __construct(Depense $depense)
    {
        $this->depense = $depense;
        $company = $depense->company;
        $agency = $depense->agency;

        $this->companyName = $company ? ($company->legal_name ?? 'PropertyAI') : 'PropertyAI';
        $this->agencyName = $agency ? $agency->name : 'Siège';

        $this->subjectTitle = "Nouvelle demande de dépense - " . $depense->reference;
        $this->title = 'Nouvelle Dépense Enregistrée';
        $this->paragraphs = [
            "Une nouvelle dépense a été enregistrée dans le système et est en attente de validation.",
            "Voici les détails de la dépense :"
        ];

        $this->details = [
            'Référence' => $depense->reference,
            'Titre' => $depense->titre,
            'Type de dépense' => $depense->typeDepense ? $depense->typeDepense->nom : ($depense->categorie ?: 'N/A'),
            'Montant' => number_format($depense->montant, 2, ',', ' ') . ' XAF',
            'Date' => $depense->date_depense ? $depense->date_depense->format('d/m/Y') : 'N/A',
            'Statut' => $depense->statut,
            'Créé par' => $agency ? "Agence : " . $agency->name : "Siège Social",
            'Description' => $depense->description ?: 'Aucune description fournie.'
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
