<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FicheCaravane extends Model
{
    protected $table = 'fiche_caravanes';

    protected $fillable = [
        'uuid',
        'user_id',
        'region',
        'departement',
        'date_caravane',
        'heure_debut',
        'heure_fin',
        'distributeurs_beneficiaires',
        'distributeurs_contacts',
        'itineraire',
        'moyens_logistiques',
        'nb_villages_sillonnes',
        'supports_affiches',
        'supports_depliants',
        'supports_autres_libelle',
        'supports_autres_nb',
        'nb_fa_vendus',
        'responsable_nom',
        'responsable_signature',
        'photo_url',
        'latitude',
        'longitude',
        'sync_status',
        'client_created_at',
    ];

    protected function casts(): array
    {
        return [
            'date_caravane' => 'date',
            'moyens_logistiques' => 'array',
            'nb_villages_sillonnes' => 'integer',
            'supports_affiches' => 'integer',
            'supports_depliants' => 'integer',
            'supports_autres_nb' => 'integer',
            'nb_fa_vendus' => 'integer',
            'latitude' => 'float',
            'longitude' => 'float',
            'client_created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function villagesTouches(): HasMany
    {
        return $this->hasMany(CaravaneVillageTouche::class, 'fiche_caravane_id');
    }
}
