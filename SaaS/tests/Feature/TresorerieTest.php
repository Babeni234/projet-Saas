<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\CompanyProfile;
use App\Models\User;
use App\Models\Locataire;
use App\Models\Contrat;
use App\Models\PaiementLoyer;
use App\Models\Facture;
use App\Models\TypeFacture;
use App\Models\EntreeFonds;
use App\Models\FraisContrat;
use App\Models\Renouvellement;
use App\Models\Tresorerie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TresorerieTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private CompanyProfile $company;
    private Agency $agency;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->company = CompanyProfile::create([
            'user_id' => $this->user->id,
            'legal_name' => 'Test Corp',
            'business_type' => 'SAS',
            'registration_number' => '123456789',
            'tax_id' => '987654321',
            'country' => 'FR',
            'address' => '1 Rue Test',
            'city' => 'Paris',
            'postal_code' => '75000',
            'legal_representative_name' => 'Rep Test',
            'legal_representative_id_number' => 'ID9999',
            'phone' => '+33123456789',
        ]);
        $this->user->update(['company_profile_id' => $this->company->id]);

        $this->agency = Agency::create([
            'company_profile_id' => $this->company->id,
            'name' => 'Agency A',
            'code' => 'AG-A',
            'status' => 'active',
        ]);
    }

    private function createLocataireAndContrat()
    {
        $tenantUser = User::factory()->create(['name' => 'John Doe']);
        $locataire = Locataire::create([
            'company_profile_id' => $this->company->id,
            'user_id' => $tenantUser->id,
            'telephone' => '123456789',
            'statut' => 'Actif',
        ]);

        $categorie = \App\Models\Categorie::create([
            'company_profile_id' => $this->company->id,
            'nom' => 'Appartement',
        ]);

        $logement = \App\Models\Logement::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency->id,
            'categorie_id' => $categorie->id,
            'reference' => 'LOG-' . uniqid(),
            'loyer' => 1000,
        ]);

        $contrat = Contrat::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency->id,
            'locataire_id' => $locataire->id,
            'logement_id' => $logement->id,
            'numero' => 'CTR-' . uniqid(),
            'loyer' => 1000,
            'caution' => 2000,
            'debut' => '2026-01-01',
            'fin' => '2027-01-01',
        ]);

        return [$locataire, $contrat, $logement];
    }

    public function test_rent_payment_syncs_to_treasury(): void
    {
        [$locataire, $contrat] = $this->createLocataireAndContrat();

        $payment = PaiementLoyer::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency->id,
            'locataire_id' => $locataire->id,
            'contrat_id' => $contrat->id,
            'date_reglement' => '2026-06-16',
            'montant_total' => 1050.00,
            'mode_reglement' => 'cash',
        ]);

        // Trigger helper directly
        Tresorerie::enregistrer(
            $payment,
            (float) $payment->montant_total,
            "Paiement de loyer de John Doe pour le contrat {$contrat->numero} (Réf: {$payment->reference})",
            '2026-06-16'
        );

        $this->assertDatabaseHas('tresoreries', [
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency->id,
            'montant' => 1050.00,
            'source_type' => PaiementLoyer::class,
            'source_id' => $payment->id,
            'motif' => "Paiement de loyer de John Doe pour le contrat {$contrat->numero} (Réf: {$payment->reference})",
            'deleted' => false,
        ]);
    }

    public function test_invoice_payment_syncs_to_treasury(): void
    {
        [$locataire, $contrat] = $this->createLocataireAndContrat();

        $typeFacture = TypeFacture::create([
            'company_profile_id' => $this->company->id,
            'nom' => 'Loyer',
        ]);

        $facture = Facture::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency->id,
            'locataire_id' => $locataire->id,
            'contrat_id' => $contrat->id,
            'type_facture_id' => $typeFacture->id,
            'periode' => '2026-06',
            'date_emission' => '2026-06-01',
            'date_echeance' => '2026-06-15',
            'items' => [],
            'total' => 350.00,
            'montant_paye' => 0.00,
            'statut' => 'Impayé',
        ]);

        // Simulate regler / payment
        $facture->update([
            'statut' => 'Payé',
            'montant_paye' => $facture->total,
            'mode_reglement' => 'cash',
        ]);

        Tresorerie::enregistrer(
            $facture,
            (float) $facture->total,
            "Règlement de la facture {$facture->numero} par John Doe",
            now()->toDateString()
        );

        $this->assertDatabaseHas('tresoreries', [
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency->id,
            'montant' => 350.00,
            'source_type' => Facture::class,
            'source_id' => $facture->id,
            'deleted' => false,
        ]);
    }

    public function test_other_fund_entry_syncs_to_treasury(): void
    {
        $entree = EntreeFonds::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency->id,
            'titre' => 'Vente de meubles',
            'description' => 'Vente de vieux lits',
            'montant' => 500.00,
            'date_entree' => '2026-06-16',
            'categorie' => 'Autre',
            'reference' => 'REF-001',
            'statut' => 'Encaissé',
        ]);

        Tresorerie::enregistrer(
            $entree,
            (float) $entree->montant,
            "Entrée de fonds : {$entree->titre} (Réf: {$entree->reference})",
            '2026-06-16'
        );

        $this->assertDatabaseHas('tresoreries', [
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency->id,
            'montant' => 500.00,
            'source_type' => EntreeFonds::class,
            'source_id' => $entree->id,
            'deleted' => false,
        ]);
    }

    public function test_contract_renewal_fees_syncs_to_treasury(): void
    {
        [$locataire, $contrat] = $this->createLocataireAndContrat();

        $renouvellement = Renouvellement::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency->id,
            'locataire_id' => $locataire->id,
            'contrat_id' => $contrat->id,
            'nouveau_loyer' => 1200,
            'cycle_paiement' => 'Mensuel',
            'duree' => '1 an',
            'frais_contrat' => 150.00,
            'statut' => 'Complete',
        ]);

        $frais = FraisContrat::create([
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency->id,
            'renouvellement_id' => $renouvellement->id,
            'montant' => 150.00,
            'date_paiement' => '2026-06-16',
        ]);

        Tresorerie::enregistrer(
            $frais,
            (float) $frais->montant,
            "Frais de renouvellement de contrat (Renouvellement #{$renouvellement->id})",
            '2026-06-16'
        );

        $this->assertDatabaseHas('tresoreries', [
            'company_profile_id' => $this->company->id,
            'agency_id' => $this->agency->id,
            'montant' => 150.00,
            'source_type' => FraisContrat::class,
            'source_id' => $frais->id,
            'deleted' => false,
        ]);
    }
}
