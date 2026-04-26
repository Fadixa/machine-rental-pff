<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'          => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    // Relations
    public function machines()
    {
        return $this->hasMany(Machine::class, 'owner_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'client_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'client_id');
    }

    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isClient(): bool { return $this->role === 'client'; }

// ✅ CORRECTION 3 : } sans point-virgule à la fin
}