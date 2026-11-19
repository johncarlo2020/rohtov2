<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PorscheQuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'porsche_registration_id',
        'attempt_number',
        'question_1_answer',
        'question_2_answer',
        'question_3_answer',
        'question_4_answer',
        'score_a',
        'score_b',
        'score_c',
        'result_type',
        'completed_at',
    ];

    protected $casts = [
        'attempt_number' => 'integer',
        'score_a' => 'integer',
        'score_b' => 'integer',
        'score_c' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(PorscheRegistration::class, 'porsche_registration_id');
    }

    /**
     * Get hyphenated answers string (e.g. A-B-A-C).
     */
    public function getAnswersStringAttribute(): string
    {
        return implode('-', array_filter([
            $this->question_1_answer,
            $this->question_2_answer,
            $this->question_3_answer,
            $this->question_4_answer,
        ]));
    }

    /**
     * Get result title from configuration.
     */
    public function getResultTitleAttribute(): string
    {
        return config("porsche.results.{$this->result_type}.title", "Result {$this->result_type}");
    }

    /**
     * Scope query to filter attempts.
     */
    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        if (!empty($filters['result_type'])) {
            $query->where('result_type', strtoupper($filters['result_type']));
        }

        if (!empty($filters['registration_id'])) {
            $query->where('porsche_registration_id', $filters['registration_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('completed_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('completed_at', '<=', $filters['date_to']);
        }

        $search = $filters['search'] ?? null;
        if (is_array($search)) {
            $search = $search['value'] ?? null;
        }
        $search = is_string($search) ? trim($search) : '';

        if (!empty($search)) {
            $searchPattern = "%{$search}%";
            $query->whereHas('registration', function (Builder $q) use ($searchPattern) {
                $q->where('full_name', 'like', $searchPattern)
                  ->orWhere('email', 'like', $searchPattern)
                  ->orWhere('contact_number', 'like', $searchPattern);
            });
        }

        return $query;
    }
}
