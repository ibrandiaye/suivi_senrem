<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FicheAnimationVente extends Model
{
    protected $table = 'fiche_animation_ventes';

    protected $fillable = [
        'uuid',
        'user_id',
        'region',
        'departement',
        'commune',
        'village_quartier',
        'lieu_animation',
        'date_animation',
        'distributeur_nom',
        'distributeur_adresse',
        'distributeur_contact',
        'distributeur_signature',
        'materiel_utilise',
        'animateur_nom',
        'animateur_contact',
        'nb_fa_vendus',
        'latitude',
        'longitude',
        'photo_url',
        'sync_status',
        'client_created_at',
    ];

    protected function casts(): array
    {
        return [
            'date_animation' => 'date',
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
}
