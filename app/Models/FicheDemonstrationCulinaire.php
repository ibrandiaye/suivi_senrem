<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FicheDemonstrationCulinaire extends Model
{
    protected $table = 'fiche_demonstrations_culinaires';

    protected $fillable = [
        'uuid',
        'user_id',
        'region',
        'departement',
        'commune',
        'village_quartier',
        'date_demonstration',
        'gpf_nom',
        'activites_principales_gpf',
        'nb_presents',
        'nb_hommes',
        'nb_femmes',
        'presidente_nom',
        'presidente_contact',
        'presidente_age',
        'consent_photo_video',
        'consent_interview',
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
            'date_demonstration' => 'date',
            'nb_presents' => 'integer',
            'nb_hommes' => 'integer',
            'nb_femmes' => 'integer',
            'presidente_age' => 'integer',
            'consent_photo_video' => 'boolean',
            'consent_interview' => 'boolean',
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
