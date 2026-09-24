<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoyerType extends Model
{
    protected $fillable = [
        'code',
        'nom',
        'combustible',
        'capacite_kg',
        'description',
        'ordre',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'ordre' => 'integer',
        ];
    }
}
