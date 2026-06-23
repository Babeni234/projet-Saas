<?php

namespace App\Mail;

use App\Models\Contrat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenewalInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contrat;
    public $step;
    public $companyName;
    public $agencyName;
    public $locataireName;
    public $logementRef;
    public $dateFin;
    public $weeksLeft;

    /**
     * Create a new message instance.
     */
    public function __construct(Contrat $contrat, int $step)
    {
        $this->contrat = $contrat;
        $this->step = $step;
        $this->companyName = $contrat->company?->legal_name ?? 'PropertyAI';
        $this->agencyName = $contrat->agency?->name ?? 'Siège';
        $this->locataireName = $contrat->locataire?->nom ?? 'Locataire';
        $this->logementRef = $contrat->logement?->reference ?? 'Logement';
        $this->dateFin = $contrat->fin ? $contrat->fin->toDateString() : now()->toDateString();
        
        // Calculate weeks left based on step (1 -> 8 weeks, 2 -> 7 weeks, 3 -> 6 weeks, 4 -> 5 weeks)
        $this->weeksLeft = 9 - $step;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjects = [
            1 => "Invitation : Renouvellement de votre contrat de bail - {$this->companyName}",
            2 => "Rappel : Renouvellement de votre contrat de bail - {$this->companyName}",
            3 => "Deuxième Rappel : Renouvellement de votre contrat de bail - {$this->companyName}",
            4 => "Dernier Rappel : Renouvellement de votre contrat de bail - {$this->companyName}",
        ];

        return new Envelope(
            subject: $subjects[$this->step] ?? "Renouvellement de votre contrat de bail - {$this->companyName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.renewal_invitation',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
