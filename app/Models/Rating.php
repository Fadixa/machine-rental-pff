<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'client_id',
        'machine_id',
        'rating',
        'comment',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}