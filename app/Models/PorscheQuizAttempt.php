<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PorscheQuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'porsche_registration_id',
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
        'score_a' => 'integer',
        'score_b' => 'integer',
        'score_c' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(PorscheRegistration::class, 'porsche_registration_id');
    }
}
