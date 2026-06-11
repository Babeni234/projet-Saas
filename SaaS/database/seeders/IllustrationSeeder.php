<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CompanyProfile;
use App\Models\Agency;
use App\Models\Illustration;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class IllustrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Find or create default user test@example.com
        $user = User::where('email', 'test@example.com')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'account_type' => 'company',
                'subscription_plan' => 'starter',
            ]);
        }

        // 2. Find or create CompanyProfile
        $companyProfile = CompanyProfile::where('user_id', $user->id)->first();
        if (!$companyProfile) {
            $companyProfile = CompanyProfile::create([
                'user_id' => $user->id,
                'business_type' => 'SAS',
                'legal_name' => 'Immo Corp',
                'registration_number' => '998877665',
                'tax_id' => '11223344',
                'country' => 'FR',
                'address' => '1 Rue Royale',
                'city' => 'Paris',
                'postal_code' => '75001',
                'legal_representative_name' => 'Ad Rep',
                'legal_representative_id_number' => 'ID1234',
                'phone' => '+33100000000',
                'verification_status' => 'approved',
            ]);
        }

        // 3. Make sure user is associated with CompanyProfile
        if (empty($user->company_profile_id)) {
            $user->company_profile_id = $companyProfile->id;
            $user->save();
        }

        // 4. Ensure roles for this CompanyProfile exist
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrateur avec accès complet à toutes les fonctionnalités.',
                'permissions' => json_encode(['*']),
            ],
            [
                'name' => "Chef d'agence",
                'slug' => 'chef_agence',
                'description' => "Gestion et supervision d'une agence spécifique.",
                'permissions' => json_encode(['manage_agencies', 'manage_properties', 'view_reports']),
            ],
            [
                'name' => 'Gestionnaire',
                'slug' => 'gestionnaire',
                'description' => 'Gestion des opérations quotidiennes et des locations.',
                'permissions' => json_encode(['manage_properties', 'view_reports']),
            ],
            [
                'name' => 'Comptable',
                'slug' => 'comptable',
                'description' => 'Accès et gestion de la comptabilité et des factures.',
                'permissions' => json_encode(['manage_accounting', 'view_reports']),
            ],
            [
                'name' => 'Maintenancier',
                'slug' => 'maintenancier',
                'description' => 'Gestion des tickets et interventions de maintenance.',
                'permissions' => json_encode(['manage_maintenance']),
            ],
            [
                'name' => 'Employé simple',
                'slug' => 'employer_simple',
                'description' => 'Employé simple avec accès de base.',
                'permissions' => json_encode(['basic_access']),
            ],
        ];

        foreach ($roles as $roleData) {
            $exists = Role::where('company_profile_id', $companyProfile->id)
                ->where('slug', $roleData['slug'])
                ->exists();
            if (!$exists) {
                Role::create(array_merge($roleData, [
                    'company_profile_id' => $companyProfile->id,
                ]));
            }
        }

        // 5. Assign user to Admin role
        $adminRole = Role::where('company_profile_id', $companyProfile->id)
            ->where('slug', 'admin')
            ->first();
        if ($adminRole && $user->role_id !== $adminRole->id) {
            $user->role_id = $adminRole->id;
            $user->save();
        }

        // 6. Ensure default agencies exist
        $agencyA = Agency::where('company_profile_id', $companyProfile->id)
            ->where('name', 'Agence Paris Centre')
            ->first();
        if (!$agencyA) {
            $agencyA = Agency::create([
                'company_profile_id' => $companyProfile->id,
                'name' => 'Agence Paris Centre',
                'code' => 'AG-PAR01',
                'status' => 'active',
                'city' => 'Paris',
                'country' => 'FR',
                'address' => '10 Rue de Louvois',
                'postal_code' => '75002',
            ]);
        }

        $agencyB = Agency::where('company_profile_id', $companyProfile->id)
            ->where('name', 'Agence Lyon Est')
            ->first();
        if (!$agencyB) {
            $agencyB = Agency::create([
                'company_profile_id' => $companyProfile->id,
                'name' => 'Agence Lyon Est',
                'code' => 'AG-LYO01',
                'status' => 'active',
                'city' => 'Lyon',
                'country' => 'FR',
                'address' => '45 Avenue Jean Jaurès',
                'postal_code' => '69007',
            ]);
        }

        // 7. Seed illustrations with online images and videos
        $illustrationsData = [
            // Bâtiment Immeuble A
            [
                'agency_id' => $agencyA->id,
                'target_type' => 'batiment',
                'target_id' => '1',
                'target_name' => 'Immeuble A',
                'file_path' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80',
                'file_name' => 'Facade_Immeuble_A.jpg',
                'media_type' => 'image',
                'mime_type' => 'image/jpeg',
                'file_size' => 245600,
                'description' => 'Vue extérieure principale et moderne de la façade de l\'Immeuble A.',
            ],
            [
                'agency_id' => $agencyA->id,
                'target_type' => 'batiment',
                'target_id' => '1',
                'target_name' => 'Immeuble A',
                'file_path' => 'https://images.unsplash.com/photo-1574362848149-11496d93a7c7?auto=format&fit=crop&w=800&q=80',
                'file_name' => 'Cour_Interieure_Immeuble_A.jpg',
                'media_type' => 'image',
                'mime_type' => 'image/jpeg',
                'file_size' => 198200,
                'description' => 'Cour intérieure arborée avec espace de détente commun.',
            ],
            // Bâtiment Immeuble B
            [
                'agency_id' => $agencyA->id,
                'target_type' => 'batiment',
                'target_id' => '2',
                'target_name' => 'Immeuble B',
                'file_path' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=800&q=80',
                'file_name' => 'Facade_Immeuble_B.jpg',
                'media_type' => 'image',
                'mime_type' => 'image/jpeg',
                'file_size' => 312000,
                'description' => 'Grand bâtiment moderne avec structure en verre et acier.',
            ],
            [
                'agency_id' => $agencyA->id,
                'target_type' => 'batiment',
                'target_id' => '2',
                'target_name' => 'Immeuble B',
                'file_path' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80',
                'file_name' => 'Hall_Reception_Immeuble_B.jpg',
                'media_type' => 'image',
                'mime_type' => 'image/jpeg',
                'file_size' => 174000,
                'description' => 'Hall de réception spacieux et lumineux de l\'Immeuble B.',
            ],
            // Bâtiment Immeuble C
            [
                'agency_id' => $agencyB->id,
                'target_type' => 'batiment',
                'target_id' => '3',
                'target_name' => 'Immeuble C',
                'file_path' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80',
                'file_name' => 'Facade_Villa_Immeuble_C.jpg',
                'media_type' => 'image',
                'mime_type' => 'image/jpeg',
                'file_size' => 450000,
                'description' => 'Extérieur de standing de la résidence de luxe Immeuble C.',
            ],
            [
                'agency_id' => $agencyB->id,
                'target_type' => 'batiment',
                'target_id' => '3',
                'target_name' => 'Immeuble C',
                'file_path' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
                'file_name' => 'Jardin_Piscine_Immeuble_C.jpg',
                'media_type' => 'image',
                'mime_type' => 'image/jpeg',
                'file_size' => 389000,
                'description' => 'Jardin paysager avec piscine extérieure commune.',
            ],

            // Logement APT-A101
            [
                'agency_id' => $agencyA->id,
                'target_type' => 'logement',
                'target_id' => '1',
                'target_name' => 'APT-A101',
                'file_path' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80',
                'file_name' => 'Salon_A101.jpg',
                'media_type' => 'image',
                'mime_type' => 'image/jpeg',
                'file_size' => 153600,
                'description' => 'Salon contemporain, lumineux et très spacieux de l\'APT-A101.',
            ],
            [
                'agency_id' => $agencyA->id,
                'target_type' => 'logement',
                'target_id' => '1',
                'target_name' => 'APT-A101',
                'file_path' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?auto=format&fit=crop&w=800&q=80',
                'file_name' => 'Chambre_A101.jpg',
                'media_type' => 'image',
                'mime_type' => 'image/jpeg',
                'file_size' => 128000,
                'description' => 'Chambre principale chaleureuse avec parquet au sol.',
            ],
            [
                'agency_id' => $agencyA->id,
                'target_type' => 'logement',
                'target_id' => '1',
                'target_name' => 'APT-A101',
                'file_path' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=800&q=80',
                'file_name' => 'Cuisine_A101.jpg',
                'media_type' => 'image',
                'mime_type' => 'image/jpeg',
                'file_size' => 165000,
                'description' => 'Cuisine moderne entièrement équipée avec comptoir.',
            ],
            [
                'agency_id' => $agencyA->id,
                'target_type' => 'logement',
                'target_id' => '1',
                'target_name' => 'APT-A101',
                'file_path' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
                'file_name' => 'Visite_Virtuelle_A101.mp4',
                'media_type' => 'video',
                'mime_type' => 'video/mp4',
                'file_size' => 2048000,
                'description' => 'Vidéo de présentation et visite guidée virtuelle de l\'appartement.',
            ],

            // Logement APT-A102
            [
                'agency_id' => $agencyA->id,
                'target_type' => 'logement',
                'target_id' => '2',
                'target_name' => 'APT-A102',
                'file_path' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80',
                'file_name' => 'Salle_de_Bain_A102.jpg',
                'media_type' => 'image',
                'mime_type' => 'image/jpeg',
                'file_size' => 140000,
                'description' => 'Salle de bain propre et élégante en carrelage blanc avec baignoire.',
            ],

            // Logement APT-B101
            [
                'agency_id' => $agencyB->id,
                'target_type' => 'logement',
                'target_id' => '3',
                'target_name' => 'APT-B101',
                'file_path' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=800&q=80',
                'file_name' => 'Studio_B101_Chambre.jpg',
                'media_type' => 'image',
                'mime_type' => 'image/jpeg',
                'file_size' => 189000,
                'description' => 'Chambre/Studio optimisé et aménagé avec goût.',
            ],
        ];

        foreach ($illustrationsData as $data) {
            $exists = Illustration::where('company_profile_id', $companyProfile->id)
                ->where('file_name', $data['file_name'])
                ->exists();

            if (!$exists) {
                Illustration::create(array_merge($data, [
                    'company_profile_id' => $companyProfile->id,
                    'synced' => 0,
                ]));
            }
        }
    }
}
