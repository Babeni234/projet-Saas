<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Logement extends Model
{
    use SoftDeletes;

    protected $table = 'logements';

    protected $fillable = [
        'uuid',
        'reference',
        'company_profile_id',
        'agency_id',
        'batiment_id',
        'categorie_id',
        'etage',
        'surface',
        'loyer',
        'statut',
        'deleted',
    ];

    protected $casts = [
        'deleted' => 'boolean',
        'loyer'   => 'decimal:2',
        'etage'   => 'integer',
        'surface' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->reference)) {
                $prefix = 'BIEN';
                if ($model->categorie_id) {
                    $cat = \App\Models\Categorie::find($model->categorie_id);
                    if ($cat && !empty($cat->nom)) {
                        // Normalize and strip accents
                        $nomLower = strtr(mb_strtolower($cat->nom, 'UTF-8'), [
                            'à'=>'a', 'â'=>'a', 'ä'=>'a', 'é'=>'e', 'è'=>'e', 'ê'=>'e', 'ë'=>'e', 
                            'î'=>'i', 'ï'=>'i', 'ô'=>'o', 'ö'=>'o', 'ù'=>'u', 'û'=>'u', 'ü'=>'u', 'ç'=>'c'
                        ]);
                        if (str_contains($nomLower, 'apparthotel')) {
                            $prefix = 'APTH';
                        } elseif (str_contains($nomLower, 'appart')) {
                            $prefix = 'APPT';
                        } elseif (str_contains($nomLower, 'chambre')) {
                            $prefix = 'CH';
                        } elseif (str_contains($nomLower, 'studio')) {
                            $prefix = 'STD';
                        } elseif (str_contains($nomLower, 'villa')) {
                            $prefix = 'VL';
                        } elseif (str_contains($nomLower, 'bureau')) {
                            $prefix = 'BUR';
                        } elseif (str_contains($nomLower, 'magasin')) {
                            $prefix = 'MAG';
                        } elseif (str_contains($nomLower, 'entrepot')) {
                            $prefix = 'ENT';
                        } else {
                            $clean = preg_replace('/[^a-zA-Z]/', '', $cat->nom);
                            $prefix = !empty($clean) ? strtoupper(substr($clean, 0, 4)) : 'BIEN';
                        }
                    }
                }
                $randomDigits = mt_rand(1000, 9999);
                $model->reference = $prefix . '-' . $randomDigits;
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function batiment(): BelongsTo
    {
        return $this->belongsTo(Batiment::class, 'batiment_id');
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }
}
