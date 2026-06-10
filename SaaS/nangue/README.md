# Espace de travail — Nangue

Tout ton code personnel vit ici sur la branche `feature/nangue`.

## Structure

| Dossier | Usage |
|---------|--------|
| `Http/Controllers/` | Contrôleurs PHP (`Nangue\Http\Controllers\...`) |
| `routes/web.php` | Routes préfixées par `/nangue` |
| `resources/js/Pages/` | Pages Vue Inertia (`Nangue/NomPage`) |
| `database/migrations/` | Migrations Laravel |
| `Models/` | Modèles Eloquent (`Nangue\Models\...`) |

## URLs (intégrées au projet)

- **Particulier** : `/espace-particulier` (`route('immo.particulier')`)
- **Bailleur pro** : `/espace-bailleur` (`route('immo.bailleur')`)
- Après connexion : `/dashboard` redirige vers l'espace particulier

## Exemples

**Route** → ajoute dans `routes/web.php`  
**Contrôleur** → `Http/Controllers/MonController.php`  
**Page Vue** → `resources/js/Pages/User/MonEcran.vue` puis `Inertia::render('Nangue/User/MonEcran')`

Les données de démo sont dans `Support/DemoData.php` (à remplacer par l’API plus tard).

Ne modifie pas les fichiers Breeze/Laravel hors de ce dossier, sauf si l’équipe le demande.
