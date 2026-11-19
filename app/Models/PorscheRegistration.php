<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function latestAttempt(): HasOne
    {
        return $this->hasOne(PorscheQuizAttempt::class, 'porsche_registration_id')->latestOfMany('id');
    }

    /**
     * Scope query for filtering participants.
     */
    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        $search = $filters['search'] ?? null;
        if (is_array($search)) {
            $search = $search['value'] ?? null;
        }
        $search = is_string($search) ? trim($search) : '';

        if (!empty($search)) {
            $searchPattern = "%{$search}%";
            $query->where(function (Builder $q) use ($searchPattern) {
                $q->where('full_name', 'like', $searchPattern)
                  ->orWhere('email', 'like', $searchPattern)
                  ->orWhere('contact_number', 'like', $searchPattern);
            });
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('registered_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('registered_at', '<=', $filters['date_to']);
        }

        return $query;
    }
}
