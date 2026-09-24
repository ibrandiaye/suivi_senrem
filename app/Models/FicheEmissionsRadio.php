<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FicheEmissionsRadio extends Model
{
    protected $table = 'fiche_emissions_radios';

    protected $fillable = [
        'uuid',
        'user_id',
        'region',
        'commune',
        'radio_nom',
        'radio_frequence',
        'date_emission',
        'heure_debut',
        'heure_fin',
        'theme',
        'format',
        'lieu',
        'animateur_nom',
        'responsable_radio_nom',
        'responsable_radio_signature',
        'audio_or_photo_url',
        'latitude',
        'longitude',
        'sync_status',
        'client_created_at',
    ];

    protected function casts(): array
    {
        return [
            'date_emission' => 'date',
            'latitude' => 'float',
            'longitude' => 'float',
            'client_created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(RadioParticipant::class, 'fiche_emissions_radio_id');
    }
}
