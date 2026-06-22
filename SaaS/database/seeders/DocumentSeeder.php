<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        DB::table('documents')->insert([
            ['name' => 'CNI Marie Dupont', 'file_path' => 'documents/cni_marie.pdf', 'file_type' => 'application/pdf', 'file_size' => 245000, 'documentable_type' => 'Nangue\Models\Tenant', 'documentable_id' => 1, 'document_category_id' => 1, 'user_id' => 1, 'expires_at' => '2028-06-01', 'verified' => true, 'verified_at' => $now, 'verified_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Bail T3 Lyon signé', 'file_path' => 'documents/bail_t3_lyon.pdf', 'file_type' => 'application/pdf', 'file_size' => 512000, 'documentable_type' => 'Nangue\Models\Contract', 'documentable_id' => 1, 'document_category_id' => 3, 'user_id' => 1, 'expires_at' => null, 'verified' => true, 'verified_at' => $now, 'verified_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'DPE T3 Lyon', 'file_path' => 'documents/dpe_t3.pdf', 'file_type' => 'application/pdf', 'file_size' => 180000, 'documentable_type' => 'Nangue\Models\Property', 'documentable_id' => 1, 'document_category_id' => 4, 'user_id' => 1, 'expires_at' => '2030-06-01', 'verified' => true, 'verified_at' => $now, 'verified_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Assurance habitation M. Martin', 'file_path' => 'documents/assurance_martin.pdf', 'file_type' => 'application/pdf', 'file_size' => 320000, 'documentable_type' => 'Nangue\Models\Tenant', 'documentable_id' => 2, 'document_category_id' => 6, 'user_id' => 1, 'expires_at' => '2026-09-01', 'verified' => false, 'verified_at' => null, 'verified_by' => null, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
