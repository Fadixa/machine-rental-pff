<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id', 'name', 'type', 'description',
        'price_per_day', 'price_per_hour', 'location', 'status',
    ];

    protected function casts(): array
    {
        return [
            'price_per_day'  => 'decimal:2',
            'price_per_hour' => 'decimal:2',
        ];
    }

    // ── Relations ──────────────────────────────────────────────────

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function images()
    {
        return $this->hasMany(MachineImage::class)->orderBy('is_primary', 'desc');
    }

    public function primaryImage()
    {
        return $this->hasOne(MachineImage::class)->where('is_primary', true);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────

    public function scopeAvailable(Builder $q): Builder
    {
        return $q->where('status', 'available');
    }

    public function scopeByType(Builder $q, string $type): Builder
    {
        return $q->where('type', $type);
    }

    public function scopeByLocation(Builder $q, string $location): Builder
    {
        return $q->where('location', 'LIKE', "%{$location}%");
    }

    public function scopeFreeOn(Builder $q, string $start, string $end): Builder
    {
        return $q->whereDoesntHave('reservations', function($r) use ($start, $end) {
            $r->whereIn('status', ['pending', 'accepted'])
              ->where('start_date', '<=', $end)
              ->where('end_date', '>=', $start);
        });
    }

    // ── Méthodes métier ─────────────────────────────────────────────

    public function isAvailableOn(string $start, string $end): bool
    {
        return ! $this->reservations()
            ->whereIn('status', ['pending', 'accepted'])
            ->where('start_date', '<=', $end)
            ->where('end_date', '>=', $start)
            ->exists();
    }

    public function calculatePrice(string $start, string $end): float
    {
        $days = \Carbon\Carbon::parse($start)->diffInDays($end) + 1;
        return round($this->price_per_day * $days, 2);
    }

    public function averageRating(): float
    {
        return round($this->ratings()->avg('rating') ?? 0, 1);
    }
}