<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaravaneVillageTouche extends Model
{
    protected $table = 'caravane_villages_touches';

    protected $fillable = [
        'fiche_caravane_id',
        'numero_ordre',
        'commune_ou_zone',
        'nom_village_quartier',
    ];

    public function ficheCaravane(): BelongsTo
    {
        return $this->belongsTo(FicheCaravane::class, 'fiche_caravane_id');
    }
}
