<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RadioParticipant extends Model
{
    protected $fillable = [
        'fiche_emissions_radio_id',
        'numero_ordre',
        'prenom_nom',
        'sexe',
        'profession',
    ];

    public function ficheEmissionRadio(): BelongsTo
    {
        return $this->belongsTo(FicheEmissionsRadio::class, 'fiche_emissions_radio_id');
    }
}
