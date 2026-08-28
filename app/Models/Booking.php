<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_date_id',
        'booking_slot_id',
        'reference_no',
        'customer_name',
        'customer_email',
        'customer_phone',
        'status',
        'reschedule_count',
    ];

    public function bookingDate(): BelongsTo
    {
        return $this->belongsTo(BookingDate::class);
    }

    public function bookingSlot(): BelongsTo
    {
        return $this->belongsTo(BookingSlot::class);
    }
}
