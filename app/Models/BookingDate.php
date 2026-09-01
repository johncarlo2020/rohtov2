<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookingDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'is_available',
        'reason',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'date' => 'date:Y-m-d',
    ];

    public function slots(): HasMany
    {
        return $this->hasMany(BookingSlot::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
