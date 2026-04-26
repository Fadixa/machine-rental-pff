<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'machine_id', 'start_date', 'end_date',
        'total_price', 'status', 'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'start_date'  => 'date',
            'end_date'    => 'date',
            'total_price' => 'decimal:2',
        ];
    }

    // ── Relations ──────────────────────────────────────────────────

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    // ── Scopes ──────────────────────────────────────────────────────

    public function scopePending(Builder $q): Builder
    {
        return $q->where('status', 'pending');
    }

    public function scopeAccepted(Builder $q): Builder
    {
        return $q->where('status', 'accepted');
    }

    public function scopeForOwner(Builder $q, int $ownerId): Builder
    {
        return $q->whereHas('machine', fn($m) => $m->where('owner_id', $ownerId));
    }

    // ── Transitions ─────────────────────────────────────────────────

    public function accept(): void
    {
        $this->update(['status' => 'accepted']);
        $this->machine->update(['status' => 'unavailable']);
    }

    public function reject(string $reason = ''): void
    {
        $this->update([
            'status'           => 'rejected',
            'rejection_reason' => $reason,
        ]);
    }

    public function complete(): void
    {
        $this->update(['status' => 'completed']);
        $this->machine->update(['status' => 'available']);
    }

    // ── Accesseurs ───────────────────────────────────────────────────

    public function getNbDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}