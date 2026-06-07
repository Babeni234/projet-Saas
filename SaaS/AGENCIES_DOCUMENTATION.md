# Documentation: Gestion des Agences

## Vue d'ensemble

Le système de gestion des agences permet une administration complète et intuitive des agences, y compris la création, modification, suppression et consultation détaillée. Il fournit également des statistiques en temps réel, des filtres avancés et des actions en masse.

## Structure des fichiers créés

### Backend

#### 1. **Migration: `database/migrations/2024_01_01_000003_create_agencies_table.php`**
- Crée la table `agencies` avec tous les champs nécessaires
- Colonnes principales:
  - `id` - Identifiant unique
  - `name` - Nom de l'agence
  - `code` - Code unique de l'agence
  - `description` - Description détaillée
  - `email`, `phone`, `fax` - Informations de contact
  - `address`, `city`, `postal_code`, `country` - Localisation
  - `latitude`, `longitude` - Coordonnées GPS
  - `manager_name`, `manager_email`, `manager_phone` - Responsable
  - `status` - État (active, inactive, suspended)
  - `employee_count` - Nombre d'employés
  - `establishment_date` - Date d'établissement
  - `metadata` - Données JSON supplémentaires
  - `timestamps` - created_at, updated_at
  - `soft_deletes` - deleted_at

#### 2. **Modèle: `app/Models/Agency.php`**
- Modèle Eloquent pour gérer les agences
- Accesseurs calculés:
  - `full_address` - Adresse complète formatée
  - `manager_contact` - Informations du responsable
  - `status_badge` - Classe CSS du badge de statut
  - `status_label` - Label lisible du statut
- Scopes:
  - `active()` - Filtre les agences actives
  - `inactive()` - Filtre les agences inactives
- Relations: À ajouter selon vos besoins (employés, propriétés, etc.)

#### 3. **Contrôleur: `app/Http/Controllers/AgencyController.php`**
- Gère toutes les opérations CRUD
- Méthodes principales:
  - `index()` - Liste paginated avec filtres
  - `create()` - Formulaire de création
  - `store()` - Sauvegarde une nouvelle agence
  - `show()` - Affiche les détails d'une agence
  - `edit()` - Formulaire de modification
  - `update()` - Met à jour une agence
  - `destroy()` - Supprime une agence
  - `getStatistics()` - Retourne les statistiques (JSON API)
  - `getAgenciesForMap()` - Retourne les agences avec coordonnées (JSON API)
  - `bulkUpdateStatus()` - Met à jour le statut de plusieurs agences
  - `export()` - Exporte en CSV

### Fronten d (Vue.js)

#### 1. **Page de liste: `resources/js/Pages/entreprise/Dashboard/pages/AgenciesManagement.vue`**
Fonctionnalités:
- Tableau complet avec pagination
- Recherche multi-champs
- Filtres par statut
- Sélection multiple
- Actions en masse (marquer active/inactive/supprimer)
- Statistiques en haut (total, actif, inactif, suspendu)
- Export en CSV
- Actions par ligne (voir, modifier, supprimer)

#### 2. **Formulaire: `resources/js/Pages/entreprise/Dashboard/pages/AgencyForm.vue`**
Sections:
- **Informations de Base**
  - Nom, Code, Description
  - Statut, Date d'établissement
  
- **Informations de Contact**
  - Email, Téléphone, Fax
  - Nombre d'employés
  
- **Localisation et Adresse**
  - Adresse, Ville, Code postal, Pays
  - Latitude, Longitude (pour localisation sur carte)
  
- **Informations du Responsable**
  - Nom, Email, Téléphone

Validations côté client et serveur incluses.

#### 3. **Détails: `resources/js/Pages/entreprise/Dashboard/pages/AgencyDetails.vue`**
Affiche:
- Informations complètes de l'agence
- Détails du responsable
- Localisation
- Statistiques (création, modification, ID)
- Actions rapides (modifier, supprimer)
- Liens vers ressources associées

#### 4. **Dashboard: `resources/js/Pages/entreprise/Dashboard/components/AgenciesDashboard.vue`**
Affiche:
- Statistiques en cartes
- Liste des 5 agences récentes
- Distribution par statut
- Actions rapides

### Routes

Les routes sont enregistrées dans `routes/web.php`:
```php
Route::prefix('agencies')->name('agencies.')->group(function () {
    Route::get('/', [AgencyController::class, 'index'])->name('index');
    Route::get('/create', [AgencyController::class, 'create'])->name('create');
    Route::post('/', [AgencyController::class, 'store'])->name('store');
    Route::get('/{agency}', [AgencyController::class, 'show'])->name('show');
    Route::get('/{agency}/edit', [AgencyController::class, 'edit'])->name('edit');
    Route::put('/{agency}', [AgencyController::class, 'update'])->name('update');
    Route::delete('/{agency}', [AgencyController::class, 'destroy'])->name('destroy');
    Route::get('/export/csv', [AgencyController::class, 'export'])->name('export');
    Route::get('/statistics', [AgencyController::class, 'getStatistics'])->name('statistics');
    Route::get('/map/agencies', [AgencyController::class, 'getAgenciesForMap'])->name('map');
    Route::post('/bulk/status', [AgencyController::class, 'bulkUpdateStatus'])->name('bulk-status');
});
```

## Installation et Utilisation

### 1. Exécuter la migration
```bash
php artisan migrate
```

### 2. Accéder à la gestion des agences
- Via le Dashboard: Menu Immobilier → Gestion des agences
- URL directe: `/agencies`

### 3. Fonctionnalités disponibles

#### Créer une agence
1. Cliquez sur "Nouvelle Agence"
2. Remplissez les champs obligatoires (nom, code, statut)
3. Complétez les informations optionnelles
4. Cliquez sur "Créer l'Agence"

#### Modifier une agence
1. Cliquez sur l'icône éditer (crayon)
2. Modifiez les champs voulus
3. Cliquez sur "Mettre à Jour"

#### Voir les détails
1. Cliquez sur l'icône voir (œil)
2. Consultez les informations complètes

#### Supprimer une agence
1. Cliquez sur l'icône supprimer (corbeille)
2. Confirmez la suppression

#### Recherche et filtres
1. Utilisez le champ de recherche pour trouver par nom, code, email, ville
2. Filtrez par statut (actif, inactif, suspendu)

#### Actions en masse
1. Sélectionnez plusieurs agences via les cases à cocher
2. Choisissez une action:
   - Marquer actives
   - Marquer inactives
   - Supprimer

#### Exporter en CSV
1. Cliquez sur le bouton d'export
2. Un fichier CSV est généré et téléchargé

## API JSON (pour intégrations externes)

### Obtenir les statistiques
```
GET /agencies/statistics
```
Retourne:
```json
{
  "total": 10,
  "active": 8,
  "inactive": 2,
  "suspended": 0,
  "total_employees": 150
}
```

### Obtenir les agences avec coordonnées GPS
```
GET /agencies/map/agencies
```

### Mettre à jour le statut de plusieurs agences
```
POST /agencies/bulk/status
{
  "ids": [1, 2, 3],
  "status": "active"
}
```

## Validations

### Côté serveur
- `name` - Requis, unique, max 255 caractères
- `code` - Requis, unique, max 50 caractères
- `email` - Email valide (optionnel)
- `phone` - Max 20 caractères
- `latitude` - Entre -90 et 90
- `longitude` - Entre -180 et 180
- `status` - Parmi: active, inactive, suspended
- `employee_count` - Entier >= 0

### Côté client
Validation HTML5 + validations personnalisées dans Vue.js

## Customisation

### Ajouter de nouveaux champs
1. Créer une migration: `php artisan make:migration add_fields_to_agencies_table`
2. Ajouter les colonnes
3. Mettre à jour le modèle `Agency` ($fillable)
4. Mettre à jour le formulaire `AgencyForm.vue`
5. Mettre à jour la liste `AgenciesManagement.vue`

### Ajouter des relations
1. Définir les relations dans le modèle `Agency`
2. Charger les données avec `with()` dans le contrôleur
3. Afficher dans les pages

### Personnaliser les couleurs/styles
- Modifiez les couleurs Tailwind dans les fichiers `.vue`
- Utilisez la palette de couleurs existante pour cohérence

## Tests suggérés

```bash
# Créer une agence de test
php artisan tinker
>>> App\Models\Agency::create([
    'name' => 'Agence Test',
    'code' => 'TEST001',
    'status' => 'active'
])

# Vérifier les statistiques
>>> App\Models\Agency::count()
```

## Notes

- Soft deletes activés: les agences supprimées restent en BD avec `deleted_at`
- Les coordonnées GPS permettront une intégration future avec des cartes
- Le JSON metadata permet d'ajouter des données custom par agence
- Export CSV inclut tous les champs pertinents

## Support

Pour des améliorations futures:
- Intégration Google Maps/Leaflet
- Assignation d'employés à une agence
- Historique des modifications
- Notifications lors de création/modification
- Import en masse depuis CSV
- Rapports et statistiques avancées
