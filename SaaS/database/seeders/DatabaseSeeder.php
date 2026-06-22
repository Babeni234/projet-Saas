<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $tables = ['properties','tenants','contracts','receipts','visits','favorites','saved_searches','conversations','conversation_participants','messages','incidents','incident_comments','inspections','inspection_items','document_categories','documents','payment_methods','subscriptions','payment_transactions','rent_revisions','deposits','insurance_guarantees','notification_logs','tenant_users','public_visit_slots','public_visit_bookings','rent_control_zones','rent_control_compliance','fiscal_years','fiscal_expenses'];
        foreach ($tables as $t) DB::table($t)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->call([
            PropertySeeder::class,
            TenantSeeder::class,
            ContractSeeder::class,
            ReceiptSeeder::class,
            VisitSeeder::class,
            ConversationSeeder::class,
            IncidentSeeder::class,
            InspectionSeeder::class,
            DocumentCategorySeeder::class,
            DocumentSeeder::class,
            PaymentMethodSeeder::class,
            SubscriptionSeeder::class,
            RentRevisionSeeder::class,
            DepositSeeder::class,
            InsuranceGuaranteeSeeder::class,
            PublicVisitSlotSeeder::class,
            RentControlZoneSeeder::class,
            FiscalYearSeeder::class,
        ]);
    }
}
