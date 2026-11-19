<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PorscheQuizAttempt;
use App\Models\PorscheRegistration;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PorscheAdminController extends Controller
{
    /**
     * Path to campaign settings file.
     */
    protected string $settingsPath;

    public function __construct()
    {
        $this->settingsPath = storage_path('app/porsche_campaign_settings.json');
    }

    /**
     * Load campaign settings with defaults.
     */
    protected function getSettings(): array
    {
        $defaults = [
            'campaign_name' => config('porsche.campaign.name', 'Porsche Cayenne – Interactive Journey Quiz'),
            'campaign_status' => 'active',
            'allow_multiple_entries' => true,
            'max_entries_per_participant' => null,
            'privacy_policy_url' => config('porsche.campaign.privacy_policy_url', 'https://www.porsche.com/malaysia/en/privacy-policy/'),
        ];

        if (File::exists($this->settingsPath)) {
            $saved = json_decode(File::get($this->settingsPath), true);
            if (is_array($saved)) {
                return array_merge($defaults, $saved);
            }
        }

        return $defaults;
    }

    /**
     * Module A: Overview Dashboard
     */
    public function overview(Request $request): View
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $regQuery = PorscheRegistration::query();
        $attemptQuery = PorscheQuizAttempt::query();

        if (!empty($dateFrom)) {
            $regQuery->whereDate('registered_at', '>=', $dateFrom);
            $attemptQuery->whereDate('completed_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $regQuery->whereDate('registered_at', '<=', $dateTo);
            $attemptQuery->whereDate('completed_at', '<=', $dateTo);
        }

        $totalRegistrations = (clone $regQuery)->count();
        $totalAttempts = (clone $attemptQuery)->count();

        $avgAttemptsPerParticipant = $totalRegistrations > 0
            ? round($totalAttempts / $totalRegistrations, 2)
            : 0;

        $countA = (clone $attemptQuery)->where('result_type', 'A')->count();
        $countB = (clone $attemptQuery)->where('result_type', 'B')->count();
        $countC = (clone $attemptQuery)->where('result_type', 'C')->count();

        $resultCounts = [
            'A' => $countA,
            'B' => $countB,
            'C' => $countC,
        ];

        $resultPercentages = [
            'A' => $totalAttempts > 0 ? round(($countA / $totalAttempts) * 100, 1) : 0,
            'B' => $totalAttempts > 0 ? round(($countB / $totalAttempts) * 100, 1) : 0,
            'C' => $totalAttempts > 0 ? round(($countC / $totalAttempts) * 100, 1) : 0,
        ];

        // Recent 10 completed entries
        $recentEntries = (clone $attemptQuery)
            ->with('registration')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        // Original Highcharts daily data
        $dailyRows = PorscheRegistration::select(
            \Illuminate\Support\Facades\DB::raw("DATE(COALESCE(registered_at, created_at)) as date"),
            \Illuminate\Support\Facades\DB::raw("COUNT(*) as count")
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $usersDaily = [];
        foreach ($dailyRows as $row) {
            if (!empty($row->date)) {
                $usersDaily[$row->date] = (int) $row->count;
            }
        }

        // Original Highcharts hourly customer registrations by date
        $registrationsPerHour = PorscheRegistration::select(
            \Illuminate\Support\Facades\DB::raw("DATE(COALESCE(registered_at, created_at)) as date"),
            \Illuminate\Support\Facades\DB::raw("HOUR(COALESCE(registered_at, created_at)) as hour_24"),
            \Illuminate\Support\Facades\DB::raw('LOWER(DATE_FORMAT(COALESCE(registered_at, created_at), "%l%p")) as hour'),
            \Illuminate\Support\Facades\DB::raw("COUNT(*) as registrations")
        )
            ->groupBy('date', 'hour_24', 'hour')
            ->orderBy('hour_24')
            ->havingRaw('hour IS NOT NULL AND hour <> ""')
            ->get()
            ->groupBy('hour')
            ->toArray();

        return view('admin.porsche.overview', compact(
            'totalRegistrations',
            'totalAttempts',
            'avgAttemptsPerParticipant',
            'resultCounts',
            'resultPercentages',
            'recentEntries',
            'usersDaily',
            'registrationsPerHour',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Module B: Participants List
     */
    public function participants(Request $request): View|JsonResponse
    {
        if ($request->ajax() || $request->wantsJson() || $request->has('draw')) {
            try {
                $totalRecords = PorscheRegistration::count();

                $search = $request->input('search.value');
                if (!is_string($search) || trim($search) === '') {
                    $search = is_string($request->input('search')) ? trim($request->input('search')) : '';
                }

                $query = PorscheRegistration::query()
                    ->withCount('quizAttempts')
                    ->with('latestAttempt');

                // Apply search & date filters
                $query->filter([
                    'search' => $search,
                    'date_from' => $request->input('date_from'),
                    'date_to' => $request->input('date_to'),
                ]);

                $filteredRecords = $query->count();

                // Ordering
                $columns = [
                    0 => 'id',
                    1 => 'full_name',
                    2 => 'contact_number',
                    3 => 'email',
                    4 => 'consent_given',
                    5 => 'quiz_attempts_count',
                    6 => 'registered_at',
                ];

                $orderColIndex = $request->input('order.0.column', 0);
                $orderDir = $request->input('order.0.dir', 'desc');
                $orderCol = $columns[$orderColIndex] ?? 'id';

                $query->orderBy($orderCol, $orderDir);

                // Pagination
                $start = (int) $request->input('start', 0);
                $length = (int) $request->input('length', 10);
                if ($length > 0) {
                    $query->skip($start)->take($length);
                }

                $participants = $query->get();

                $data = [];
                foreach ($participants as $p) {
                    $latest = $p->latestAttempt;

                    $data[] = [
                        'id' => '#' . $p->id,
                        'full_name' => e($p->full_name),
                        'contact_number' => e($p->contact_number),
                        'email' => e($p->email),
                        'consent' => $p->consent_given ? 'Yes' : 'No',
                        'q1' => $latest?->question_1_answer ?? '-',
                        'q2' => $latest?->question_2_answer ?? '-',
                        'q3' => $latest?->question_3_answer ?? '-',
                        'q4' => $latest?->question_4_answer ?? '-',
                        'result' => $latest?->result_type ? 'Result ' . $latest->result_type : '-',
                        'total_entries' => (int) $p->quiz_attempts_count,
                        'registered_at' => $p->registered_at ? $p->registered_at->format('Y-m-d H:i') : '-',
                        'last_activity' => $latest?->completed_at ? $latest->completed_at->format('Y-m-d H:i') : '-',
                        'actions' => '<a href="' . route('admin.participants.show', $p->id) . '" class="btn btn-xs btn-outline-dark mb-0"><i class="fa fa-eye me-1"></i> View</a>',
                    ];
                }

                return response()->json([
                    'draw' => (int) $request->input('draw', 1),
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $filteredRecords,
                    'data' => $data,
                ]);
            } catch (\Throwable $e) {
                \Log::error('Participants DataTable error: ' . $e->getMessage());
                return response()->json([
                    'draw' => (int) $request->input('draw', 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return view('admin.porsche.participants');
    }

    /**
     * Module B: Participant Detail View
     */
    public function participantShow(int $id): View
    {
        $participant = PorscheRegistration::with([
            'quizAttempts' => fn($q) => $q->orderBy('attempt_number', 'desc'),
        ])->findOrFail($id);

        $resultsConfig = config('porsche.results', []);

        return view('admin.porsche.participant-detail', compact('participant', 'resultsConfig'));
    }

    /**
     * Module C: Quiz Entries List
     */
    public function entries(Request $request): View|JsonResponse
    {
        if ($request->ajax() || $request->wantsJson() || $request->has('draw')) {
            try {
                $totalRecords = PorscheQuizAttempt::count();

                $search = $request->input('search.value');
                if (!is_string($search) || trim($search) === '') {
                    $search = is_string($request->input('search')) ? trim($request->input('search')) : '';
                }

                $query = PorscheQuizAttempt::query()->with('registration');

                // Apply filters
                $query->filter([
                    'result_type' => $request->input('result_type'),
                    'registration_id' => $request->input('registration_id'),
                    'date_from' => $request->input('date_from'),
                    'date_to' => $request->input('date_to'),
                    'search' => $search,
                ]);

                $filteredRecords = $query->count();

                // Ordering
                $columns = [
                    0 => 'id',
                    1 => 'porsche_registration_id',
                    2 => 'attempt_number',
                    3 => 'question_1_answer',
                    4 => 'result_type',
                    5 => 'completed_at',
                ];

                $orderColIndex = $request->input('order.0.column', 0);
                $orderDir = $request->input('order.0.dir', 'desc');
                $orderCol = $columns[$orderColIndex] ?? 'id';

                $query->orderBy($orderCol, $orderDir);

                // Pagination
                $start = (int) $request->input('start', 0);
                $length = (int) $request->input('length', 10);
                if ($length > 0) {
                    $query->skip($start)->take($length);
                }

                $entries = $query->get();

                $badgeColors = [
                    'A' => 'bg-gradient-danger',
                    'B' => 'bg-gradient-warning',
                    'C' => 'bg-gradient-success',
                ];

                $data = [];
                foreach ($entries as $e) {
                    $badgeClass = $badgeColors[$e->result_type] ?? 'bg-gradient-secondary';
                    $participantName = $e->registration?->full_name ?? 'Participant #' . $e->porsche_registration_id;
                    $participantEmail = $e->registration?->email ?? '';

                    $data[] = [
                        'id' => '#' . $e->id,
                        'participant' => '<div><strong>' . e($participantName) . '</strong><br><small class="text-muted">' . e($participantEmail) . '</small></div>',
                        'attempt_number' => '<span class="badge bg-light text-dark border">#' . $e->attempt_number . '</span>',
                        'answers' => '<span class="font-monospace fw-bold text-dark px-2 py-1 bg-gray-100 rounded">' . e($e->answers_string) . '</span>',
                        'result' => '<span class="badge ' . $badgeClass . '">Result ' . e($e->result_type) . '</span> <small class="text-muted d-none d-lg-inline">' . e($e->result_title) . '</small>',
                        'completed_at' => $e->completed_at ? $e->completed_at->format('Y-m-d H:i') : '-',
                    ];
                }

                return response()->json([
                    'draw' => (int) $request->input('draw', 1),
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $filteredRecords,
                    'data' => $data,
                ]);
            } catch (\Throwable $e) {
                \Log::error('Quiz Entries DataTable error: ' . $e->getMessage());
                return response()->json([
                    'draw' => (int) $request->input('draw', 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return view('admin.porsche.entries');
    }

    /**
     * Module C: Entry Detail Modal Content (JSON)
     */
    public function entryDetail(int $id): JsonResponse
    {
        $attempt = PorscheQuizAttempt::with('registration')->findOrFail($id);

        $questionsConfig = config('porsche.questions', []);
        $resultsConfig = config('porsche.results', []);

        $breakdown = [];
        for ($i = 1; $i <= 4; $i++) {
            $ansKey = "question_{$i}_answer";
            $ansChoice = $attempt->$ansKey;
            $qData = $questionsConfig[$i] ?? [];
            $breakdown[] = [
                'question_number' => $i,
                'question_text' => $qData['question'] ?? "Question {$i}",
                'selected_choice' => $ansChoice,
                'choice_text' => $qData['options'][$ansChoice] ?? 'Option ' . $ansChoice,
            ];
        }

        $resultData = $resultsConfig[$attempt->result_type] ?? [];

        return response()->json([
            'id' => $attempt->id,
            'attempt_number' => $attempt->attempt_number,
            'participant_name' => $attempt->registration?->full_name ?? 'N/A',
            'participant_email' => $attempt->registration?->email ?? 'N/A',
            'participant_contact' => $attempt->registration?->contact_number ?? 'N/A',
            'answers_string' => $attempt->answers_string,
            'score_a' => $attempt->score_a,
            'score_b' => $attempt->score_b,
            'score_c' => $attempt->score_c,
            'result_type' => $attempt->result_type,
            'result_title' => $resultData['title'] ?? "Result {$attempt->result_type}",
            'result_subtitle' => $resultData['subtitle'] ?? '',
            'result_description' => $resultData['description'] ?? '',
            'completed_at' => $attempt->completed_at ? $attempt->completed_at->format('Y-m-d H:i:s') : 'N/A',
            'breakdown' => $breakdown,
        ]);
    }

    /**
     * Module D: Reports Dashboard
     */
    public function reports(Request $request): View
    {
        $totalParticipants = PorscheRegistration::count();
        $totalEntries = PorscheQuizAttempt::count();

        $countA = PorscheQuizAttempt::where('result_type', 'A')->count();
        $countB = PorscheQuizAttempt::where('result_type', 'B')->count();
        $countC = PorscheQuizAttempt::where('result_type', 'C')->count();

        $avgEntries = $totalParticipants > 0 ? round($totalEntries / $totalParticipants, 2) : 0;

        return view('admin.porsche.reports', compact(
            'totalParticipants',
            'totalEntries',
            'countA',
            'countB',
            'countC',
            'avgEntries'
        ));
    }

    /**
     * Module D: Export Participants to CSV
     */
    public function exportParticipants(Request $request): StreamedResponse
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $filename = 'porsche_participants_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($dateFrom, $dateTo) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Participant ID',
                'Full Name',
                'Contact Number',
                'Email',
                'Consent Given',
                'Q1 Answer',
                'Q2 Answer',
                'Q3 Answer',
                'Q4 Answer',
                'Latest Result Type',
                'Latest Result Title',
                'Total Completed Attempts',
                'Registered At',
                'Latest Activity At',
            ]);

            $query = PorscheRegistration::query()
                ->with(['latestAttempt', 'quizAttempts'])
                ->when($dateFrom, fn($q) => $q->whereDate('registered_at', '>=', $dateFrom))
                ->when($dateTo, fn($q) => $q->whereDate('registered_at', '<=', $dateTo))
                ->orderBy('id', 'desc');

            $query->chunk(200, function ($participants) use ($handle) {
                foreach ($participants as $p) {
                    $latest = $p->latestAttempt;
                    fputcsv($handle, [
                        $p->id,
                        $p->full_name,
                        $p->contact_number,
                        $p->email,
                        $p->consent_given ? 'Yes' : 'No',
                        $latest?->question_1_answer ?? '-',
                        $latest?->question_2_answer ?? '-',
                        $latest?->question_3_answer ?? '-',
                        $latest?->question_4_answer ?? '-',
                        $latest?->result_type ?? 'None',
                        $latest?->result_title ?? 'N/A',
                        $p->quizAttempts->count(),
                        $p->registered_at?->format('Y-m-d H:i:s') ?? '',
                        $latest?->completed_at?->format('Y-m-d H:i:s') ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Module D: Export Quiz Entries to CSV
     */
    public function exportEntries(Request $request): StreamedResponse
    {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $resultType = $request->input('result_type');

        $filename = 'porsche_quiz_entries_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($dateFrom, $dateTo, $resultType) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Entry ID',
                'Participant ID',
                'Full Name',
                'Contact Number',
                'Email',
                'Attempt Number',
                'Q1 Answer',
                'Q2 Answer',
                'Q3 Answer',
                'Q4 Answer',
                'Score A',
                'Score B',
                'Score C',
                'Result Type',
                'Result Title',
                'Completed At',
            ]);

            $query = PorscheQuizAttempt::query()
                ->with('registration')
                ->when($resultType, fn($q) => $q->where('result_type', strtoupper($resultType)))
                ->when($dateFrom, fn($q) => $q->whereDate('completed_at', '>=', $dateFrom))
                ->when($dateTo, fn($q) => $q->whereDate('completed_at', '<=', $dateTo))
                ->orderBy('id', 'desc');

            $query->chunk(200, function ($entries) use ($handle) {
                foreach ($entries as $e) {
                    fputcsv($handle, [
                        $e->id,
                        $e->porsche_registration_id,
                        $e->registration?->full_name ?? 'N/A',
                        $e->registration?->contact_number ?? 'N/A',
                        $e->registration?->email ?? 'N/A',
                        $e->attempt_number,
                        $e->question_1_answer,
                        $e->question_2_answer,
                        $e->question_3_answer,
                        $e->question_4_answer,
                        $e->score_a,
                        $e->score_b,
                        $e->score_c,
                        $e->result_type,
                        $e->result_title,
                        $e->completed_at?->format('Y-m-d H:i:s') ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Module E: Campaign Settings View
     */
    public function settings(): View
    {
        $settings = $this->getSettings();
        return view('admin.porsche.settings', compact('settings'));
    }

    /**
     * Module E: Save Campaign Settings
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'campaign_name' => 'required|string|max:255',
            'campaign_status' => 'required|in:active,paused',
            'allow_multiple_entries' => 'nullable|boolean',
            'max_entries_per_participant' => 'nullable|integer|min:1',
            'privacy_policy_url' => 'required|url|max:255',
        ]);

        $settings = [
            'campaign_name' => $validated['campaign_name'],
            'campaign_status' => $validated['campaign_status'],
            'allow_multiple_entries' => (bool) ($request->input('allow_multiple_entries', 0)),
            'max_entries_per_participant' => !empty($validated['max_entries_per_participant']) ? (int) $validated['max_entries_per_participant'] : null,
            'privacy_policy_url' => $validated['privacy_policy_url'],
            'updated_at' => Carbon::now()->toDateTimeString(),
        ];

        File::put($this->settingsPath, json_encode($settings, JSON_PRETTY_PRINT));

        return redirect()->route('admin.settings')->with('success', 'Campaign settings updated successfully.');
    }
}
