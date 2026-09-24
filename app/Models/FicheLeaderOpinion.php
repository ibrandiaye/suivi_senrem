<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FicheLeaderOpinion extends Model
{
    protected $table = 'fiche_leaders_opinions';

    protected $fillable = [
        'uuid',
        'user_id',
        'region',
        'departement',
        'commune',
        'village_quartier',
        'lieu_habitation',
        'prenom_nom',
        'sexe',
        'titre_profession',
        'telephone',
        'email',
        'membre_entites',
        'fonctions_entites',
        'localites_influence',
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
