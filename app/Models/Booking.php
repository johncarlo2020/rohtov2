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
        'vip_name',
        'customer_email',
        'customer_phone',
        'venue',
        'is_vip',
        'pax',
        'status',
        'attended_at',
        'reschedule_count',
    ];

    protected $casts = [
        'is_vip' => 'boolean',
        'pax' => 'integer',
        'attended_at' => 'datetime',
    ];

    public function bookingDate(): BelongsTo
    {
        return $this->belongsTo(BookingDate::class);
    }

    public function bookingSlot(): BelongsTo
    {
        return $this->belongsTo(BookingSlot::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_email', 'email');
    }

    /**
     * Compute real-time attendance status:
     * - 'Attended' if marked attended / attended_at present
     * - 'Missed' if session end time has passed and not attended
     * - 'Not Yet Attended' otherwise
     */
    public function getComputedStatusAttribute(): string
    {
        $rawStatus = strtolower(trim($this->status ?? ''));
        if (in_array($rawStatus, ['attended', 'completed']) || !is_null($this->attended_at)) {
            return 'Attended';
        }

        if (in_array($rawStatus, ['missed', 'no_show'])) {
            return 'Missed';
        }

        // Check if slot has passed
        if ($this->bookingDate && $this->bookingSlot) {
            try {
                $dateStr = \Carbon\Carbon::parse($this->bookingDate->date)->format('Y-m-d');
                $endTimeStr = $this->bookingSlot->end_time;
                $slotEndDateTime = \Carbon\Carbon::parse($dateStr . ' ' . $endTimeStr);
                
                if (now()->greaterThan($slotEndDateTime)) {
                    return 'Missed';
                }
            } catch (\Throwable $e) {
                // fallback
            }
        }

        return 'Not Yet Attended';
    }
}
