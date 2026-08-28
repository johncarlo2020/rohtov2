<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OperatingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week',
        'is_open',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'day_of_week' => 'integer',
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(OperatingSession::class);
    }
}
