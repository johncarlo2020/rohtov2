<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PorscheRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'contact_number',
        'email',
        'consent_given',
        'consent_timestamp',
        'registered_at',
    ];

    protected $casts = [
        'consent_given' => 'boolean',
        'consent_timestamp' => 'datetime',
        'registered_at' => 'datetime',
    ];

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(PorscheQuizAttempt::class, 'porsche_registration_id');
    }
}
