<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('payment_methods')->insert([
            [
                'user_id' => 1, 'type' => 'card', 'provider' => 'Stripe',
                'provider_id' => 'pm_card_lyon', 'last_four' => '4242', 'brand' => 'Visa',
                'iban_last_four' => null, 'bic' => null, 'mandate_id' => null, 'mandate_signed_at' => null,
                'is_default' => true, 'is_active' => true,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'user_id' => 1, 'type' => 'sepa', 'provider' => 'GoCardless',
                'provider_id' => 'mandate_sepa_001', 'last_four' => null, 'brand' => null,
                'iban_last_four' => '6789', 'bic' => 'BNPAFRPP', 'mandate_id' => 'MD001',
                'mandate_signed_at' => '2025-01-15',
                'is_default' => false, 'is_active' => true,
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }
}
