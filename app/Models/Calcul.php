<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Calcul lancé dans l'outil par un compte. Les résultats vivent dans le moteur (effacés après
 * config('opennat.conservation_heures') heures) ; cette ligne garde l'historique.
 */
class Calcul extends Model
{
    protected $fillable = ['user_id', 'moteur_id', 'projet_identifiant', 'projet_nom', 'tirages', 'etat'];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resultatsDisponibles(): bool
    {
        return $this->created_at !== null
            && $this->created_at->greaterThan(now()->subHours((float) config('opennat.conservation_heures')));
    }
}
