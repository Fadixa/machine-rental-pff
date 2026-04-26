<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineImage extends Model
{
    protected $fillable = ['machine_id', 'image_url', 'is_primary'];
    protected function casts(): array { return ['is_primary' => 'boolean']; }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function getFullUrlAttribute(): string
    {
        return asset('storage/' . $this->image_url);
    }
}