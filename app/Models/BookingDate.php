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

    public function getDisplayDateAttribute(): string
    {
        if (!$this->date) return 'N/A';
        $dateObj = \Carbon\Carbon::parse($this->date);
        $day = $dateObj->day;
        $suffix = 'TH';
        if (!in_array($day, [11, 12, 13])) {
            switch ($day % 10) {
                case 1: $suffix = 'ST'; break;
                case 2: $suffix = 'ND'; break;
                case 3: $suffix = 'RD'; break;
            }
        }
        return $day . $suffix . ' ' . strtoupper($dateObj->format('F Y'));
    }
}
