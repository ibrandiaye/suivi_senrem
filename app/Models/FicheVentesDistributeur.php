<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FicheVentesDistributeur extends Model
{
    protected $table = 'fiche_ventes_distributeurs';

    protected $fillable = [
        'uuid',
        'user_id',
        'distributeur_nom',
        'distributeur_statut',
        'distributeur_telephone',
        'region',
        'departement',
        'commune',
        'village_quartier',
        'adresse_client',
        'date_vente',
        'total_fa_vendus',
        'distributeur_signature',
        'photo_url',
        'latitude',
        'longitude',
        'sync_status',
        'client_created_at',
    ];

    protected function casts(): array
    {
        return [
            'date_vente' => 'date',
            'total_fa_vendus' => 'integer',
            'latitude' => 'float',
            'longitude' => 'float',
            'client_created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(VenteDistributeurItem::class, 'fiche_ventes_distributeur_id');
    }
}
