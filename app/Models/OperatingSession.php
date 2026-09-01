<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperatingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'operating_hour_id',
        'start_time',
        'end_time',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capacity' => 'integer',
    ];

    public function operatingHour(): BelongsTo
    {
        return $this->belongsTo(OperatingHour::class);
    }
}
