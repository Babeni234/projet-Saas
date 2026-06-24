<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Seed default countries to ensure referential integrity
        $countries = [
            ['name' => 'France', 'code' => 'FR', 'latitude' => 46.227638, 'longitude' => 2.213749],
            ['name' => 'Belgique', 'code' => 'BE', 'latitude' => 50.503887, 'longitude' => 4.469936],
            ['name' => 'Suisse', 'code' => 'CH', 'latitude' => 46.818188, 'longitude' => 8.227511],
            ['name' => 'Allemagne', 'code' => 'DE', 'latitude' => 51.165691, 'longitude' => 10.451526],
            ['name' => 'Royaume-Uni', 'code' => 'GB', 'latitude' => 55.378051, 'longitude' => -3.435973],
            ['name' => 'États-Unis', 'code' => 'US', 'latitude' => 37.09024, 'longitude' => -95.712891],
            ['name' => 'Canada', 'code' => 'CA', 'latitude' => 56.130366, 'longitude' => -106.346771],
            ['name' => 'Émirats arabes unis', 'code' => 'AE', 'latitude' => 23.424076, 'longitude' => 53.847818],
            ['name' => 'Maroc', 'code' => 'MA', 'latitude' => 31.791702, 'longitude' => -7.09262],
            ['name' => 'Sénégal', 'code' => 'SN', 'latitude' => 14.497401, 'longitude' => -14.452362],
            ['name' => 'Côte d\'Ivoire', 'code' => 'CI', 'latitude' => 7.539989, 'longitude' => -5.54708],
            ['name' => 'Cameroun', 'code' => 'CM', 'latitude' => 7.369722, 'longitude' => 12.354722],
            ['name' => 'Espagne', 'code' => 'ES', 'latitude' => 40.463667, 'longitude' => -3.74922],
            ['name' => 'Italie', 'code' => 'IT', 'latitude' => 41.87194, 'longitude' => 12.56738],
            ['name' => 'Portugal', 'code' => 'PT', 'latitude' => 39.399872, 'longitude' => -8.224454],
            ['name' => 'Pays-Bas', 'code' => 'NL', 'latitude' => 52.132633, 'longitude' => 5.291266],
            ['name' => 'Luxembourg', 'code' => 'LU', 'latitude' => 49.815273, 'longitude' => 6.129583],
            ['name' => 'Monaco', 'code' => 'MC', 'latitude' => 43.738418, 'longitude' => 7.424616],
            ['name' => 'Arabie saoudite', 'code' => 'SA', 'latitude' => 23.885942, 'longitude' => 45.079162],
            ['name' => 'Qatar', 'code' => 'QA', 'latitude' => 25.354826, 'longitude' => 51.183884],
            ['name' => 'Autre pays', 'code' => 'XX', 'latitude' => 0.0, 'longitude' => 0.0],
        ];

        foreach ($countries as $c) {
            DB::table('countries')->updateOrInsert(
                ['code' => $c['code']],
                [
                    'name' => $c['name'],
                    'latitude' => $c['latitude'],
                    'longitude' => $c['longitude'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 2. Ensure all existing companies have a country that exists in countries table
        $validCodes = DB::table('countries')->pluck('code')->toArray();
        $invalidCompanies = DB::table('company_profiles')
            ->whereNotIn('country', $validCodes)
            ->get();

        foreach ($invalidCompanies as $comp) {
            DB::table('company_profiles')
                ->where('id', $comp->id)
                ->update(['country' => 'CM']); // default to CM (Cameroon) which exists
        }

        // 3. Add foreign key constraint
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->foreign('country')->references('code')->on('countries')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropForeign(['country']);
        });
    }
};
