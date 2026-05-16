<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Driver extends Model
{
    protected $fillable = [
        'user_id', 'numero_permis', 'categorie_permis', 'statut',
        'competences', 'telephone', 'latitude_actuelle', 'longitude_actuelle',
        'derniere_position_at', 'missions_completees', 'note_moyenne', 'actif'
    ];

    protected $casts = [
        'competences'          => 'array',
        'derniere_position_at' => 'datetime',
        'latitude_actuelle'    => 'decimal:7',
        'longitude_actuelle'   => 'decimal:7',
        'note_moyenne'         => 'decimal:2',
    ];

    // ─── Relations ────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function missions(): HasMany
    {
        return $this->hasMany(Mission::class);
    }

    public function missionEnCours(): ?Mission
    {
        return $this->missions()
            ->whereIn('statut', ['assignee', 'en_route', 'sur_place', 'en_cours'])
            ->latest()
            ->first();
    }

    // ─── Scopes ────────────────────────────────────────────
    public function scopeDisponible(Builder $query): Builder
    {
        return $query->where('statut', 'disponible')->where('actif', true);
    }

    public function scopeAvecPosition(Builder $query): Builder
    {
        return $query->whereNotNull('latitude_actuelle')->whereNotNull('longitude_actuelle');
    }

    // ─── Helpers ───────────────────────────────────────────
    public function getBadgeStatutAttribute(): string
    {
        return match($this->statut) {
            'disponible'   => '<span class="badge bg-success">Disponible</span>',
            'en_mission'   => '<span class="badge bg-warning text-dark">En mission</span>',
            'indisponible' => '<span class="badge bg-secondary">Indisponible</span>',
            'conge'        => '<span class="badge bg-info">Congé</span>',
            default        => '<span class="badge bg-light text-dark">Inconnu</span>',
        };
    }
}