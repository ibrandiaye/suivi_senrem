<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commune extends Model
{
    protected $fillable = ['departement_id', 'nom'];

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }
}
