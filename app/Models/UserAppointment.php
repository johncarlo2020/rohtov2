<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

class UserAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'appointment_id',
        'rescheduled',
        'is_attended',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
