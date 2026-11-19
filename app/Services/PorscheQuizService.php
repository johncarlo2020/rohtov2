<?php

namespace App\Services;

use App\Models\PorscheQuizAttempt;
use App\Models\PorscheRegistration;
use Carbon\Carbon;

class PorscheQuizService
{
    /**
     * Calculate quiz scores and determine result type (A, B, or C).
     *
     * @param array<int|string, string> $answers Map of question index => answer ('A', 'B', 'C')
     * @return array{result_type: string, score_a: int, score_b: int, score_c: int}
     */
    public function calculateResult(array $answers): array
    {
        $scores = [
            'A' => 0,
            'B' => 0,
            'C' => 0,
        ];

        foreach ($answers as $ans) {
            $upper = strtoupper(trim((string)$ans));
            if (isset($scores[$upper])) {
                $scores[$upper]++;
            }
        }

        $maxScore = max($scores);

        // Find all categories achieving the highest score
        $topCategories = array_keys(array_filter($scores, fn($val) => $val === $maxScore));

        // If tie, pick randomly only from the top scoring categories
        $resultType = count($topCategories) > 1
            ? $topCategories[array_rand($topCategories)]
            : ($topCategories[0] ?? 'A');

        return [
            'result_type' => $resultType,
            'score_a' => $scores['A'],
            'score_b' => $scores['B'],
            'score_c' => $scores['C'],
        ];
    }

    /**
     * Save registration to database.
     */
    public function registerUser(array $data): PorscheRegistration
    {
        $reg = PorscheRegistration::create([
            'full_name' => trim($data['full_name'] ?? $data['name']),
            'contact_number' => trim($data['contact_number'] ?? $data['phone']),
            'email' => strtolower(trim($data['email'])),
            'consent_given' => true,
            'consent_timestamp' => Carbon::now(),
            'registered_at' => Carbon::now(),
        ]);

        // Keep in sync with User table for current /admin dashboard and current export
        $nameParts = explode(' ', $reg->full_name, 2);
        \App\Models\User::updateOrCreate(
            ['email' => $reg->email],
            [
                'fname' => $nameParts[0] ?? $reg->full_name,
                'lname' => $nameParts[1] ?? '',
                'number' => $reg->contact_number,
                'marketing' => 1,
                'password' => bcrypt('Porsche@' . rand(1000, 9999)),
            ]
        );

        return $reg;
    }

    /**
     * Record a completed quiz attempt for a registration.
     */
    public function recordAttempt(int $registrationId, array $answers): PorscheQuizAttempt
    {
        $calculated = $this->calculateResult($answers);
        $nextAttemptNumber = PorscheQuizAttempt::where('porsche_registration_id', $registrationId)->count() + 1;

        return PorscheQuizAttempt::create([
            'porsche_registration_id' => $registrationId,
            'attempt_number' => $nextAttemptNumber,
            'question_1_answer' => strtoupper($answers[1] ?? 'A'),
            'question_2_answer' => strtoupper($answers[2] ?? 'A'),
            'question_3_answer' => strtoupper($answers[3] ?? 'A'),
            'question_4_answer' => strtoupper($answers[4] ?? 'A'),
            'score_a' => $calculated['score_a'],
            'score_b' => $calculated['score_b'],
            'score_c' => $calculated['score_c'],
            'result_type' => $calculated['result_type'],
            'completed_at' => Carbon::now(),
        ]);
    }
}
