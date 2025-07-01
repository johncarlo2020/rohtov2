<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public function appointmentDate()
    {
        return $this->belongsTo(AppointmentDate::class);
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}
