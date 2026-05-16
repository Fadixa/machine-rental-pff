<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mission extends Model
{
    protected $fillable = [
        'driver_id', 'reservation_id', 'statut',
        'lat_depart', 'lng_depart', 'adresse_depart',
        'lat_destination', 'lng_destination', 'adresse_destination',
        'lat_actuelle', 'lng_actuelle',
        'heure_depart_prevue', 'heure_depart_reelle',
        'heure_arrivee_prevue', 'heure_arrivee_reelle', 'heure_fin_reelle',
        'distance_km', 'duree_estimee_min',
        'instructions', 'notes_chauffeur',
        'note_client', 'commentaire_client',
    ];

    protected $casts = [
        'heure_depart_prevue'   => 'datetime',
        'heure_depart_reelle'   => 'datetime',
        'heure_arrivee_prevue'  => 'datetime',
        'heure_arrivee_reelle'  => 'datetime',
        'heure_fin_reelle'      => 'datetime',
        'lat_depart'            => 'decimal:7',
        'lng_depart'            => 'decimal:7',
        'lat_destination'       => 'decimal:7',
        'lng_destination'       => 'decimal:7',
        'lat_actuelle'          => 'decimal:7',
        'lng_actuelle'          => 'decimal:7',
        'distance_km'           => 'decimal:2',
    ];

    // ─── Relations ────────────────────────────────────────
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

  
    // ─── Scopes ────────────────────────────────────────────
public function scopeActive(Builder $query): Builder
{
    return $query->whereIn('statut', ['assignee', 'en_route', 'sur_place', 'en_cours']);
}

public function scopeAujourdhui(Builder $query): Builder
{
    return $query->whereDate('heure_depart_prevue', today());
}





    


    

    


    // ─── Helpers ───────────────────────────────────────────
    public function getStatutIconAttribute(): string
    {
        return match($this->statut) {
            'assignee'  => '📋',
            'en_route'  => '🚗',
            'sur_place' => '📍',
            'en_cours'  => '⚙️',
            'terminee'  => '✅',
            'annulee'   => '❌',
            default     => '❓',
        };
    }

    public function getDureeTravailAttribute(): ?int
    {
        if ($this->heure_depart_reelle && $this->heure_fin_reelle) {
            return $this->heure_depart_reelle->diffInMinutes($this->heure_fin_reelle);
        }
        return null;
    }

    public function estEnRetard(): bool
    {
        if ($this->heure_arrivee_prevue && !$this->heure_arrivee_reelle) {
            return now()->greaterThan($this->heure_arrivee_prevue);
        }
        return false;
    }
}