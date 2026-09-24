<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenteDistributeurItem extends Model
{
    protected $fillable = [
        'fiche_ventes_distributeur_id',
        'foyer_type_id',
        'quantite',
    ];

    protected function casts(): array
    {
        return [
            'quantite' => 'integer',
        ];
    }

    public function ficheVentesDistributeur(): BelongsTo
    {
        return $this->belongsTo(FicheVentesDistributeur::class, 'fiche_ventes_distributeur_id');
    }

    public function foyerType(): BelongsTo
    {
        return $this->belongsTo(FoyerType::class, 'foyer_type_id');
    }
}
