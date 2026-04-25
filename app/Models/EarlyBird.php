<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarlyBird extends Model
{
    protected $fillable = [
        'email',
        'name',
        'claimed',
    ];

    protected $casts = [
        'claimed' => 'boolean',
    ];

    /**
     * Optional: normalize email before saving
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    /**
     * Scope: only unclaimed early birds
     */
    public function scopeUnclaimed($query)
    {
        return $query->where('claimed', false);
    }
}
