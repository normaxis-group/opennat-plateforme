<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Compte OpenNAT.
 *
 * Inactif à l'inscription ; l'accès à l'outil et au recueil suppose une adresse vérifiée et une validation par IRICE
 * (is_active). is_admin ouvre l'administration (/admin) aux personnes d'IRICE.
 */
class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'first_name', 'last_name', 'email', 'organisation', 'fonction', 'phone', 'password',
        'is_active', 'is_admin', 'validated_at', 'validated_by', 'conditions_acceptees_le',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'validated_at' => 'datetime',
            'conditions_acceptees_le' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_admin' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin && $this->is_active && $this->hasVerifiedEmail();
    }

    /** @return HasMany<Calcul, $this> */
    public function calculs(): HasMany
    {
        return $this->hasMany(Calcul::class);
    }

    /** @return BelongsTo<User, $this> */
    public function validePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function estEnAttente(): bool
    {
        return ! $this->is_active && $this->validated_at === null;
    }
}
