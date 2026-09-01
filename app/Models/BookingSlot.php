<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_date_id',
        'start_time',
        'end_time',
        'capacity',
        'booked_count',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'capacity' => 'integer',
        'booked_count' => 'integer',
    ];

    public function bookingDate(): BelongsTo
    {
        return $this->belongsTo(BookingDate::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Check if slot has remaining capacity and is marked available.
     */
    public function isSlotAvailable(): bool
    {
        return $this->is_available && ($this->booked_count < $this->capacity);
    }

    public function getDisplayTimeAttribute(): string
    {
        if (!$this->start_time) return 'N/A';
        return strtoupper(\Carbon\Carbon::parse($this->start_time)->format('g:iA'));
    }
}
