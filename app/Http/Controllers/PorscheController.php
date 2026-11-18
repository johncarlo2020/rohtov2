<?php

namespace App\Http\Controllers;

use App\Models\PorscheQuizAttempt;
use App\Models\PorscheRegistration;
use App\Services\PorscheQuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PorscheController extends Controller
{
    public function __construct(
        protected PorscheQuizService $quizService
    ) {}

    /**
     * Display the Porsche Cayenne interactive microsite.
     */
    public function index(Request $request): View
    {
        $config = config('porsche');
        $registrationId = session('porsche_registration_id');
        $registration = $registrationId ? PorscheRegistration::find($registrationId) : null;
        $initialStep = $request->query('step', 'landing');

        return view('porsche.index', compact('config', 'registration', 'initialStep'));
    }

    /**
     * Display the Porsche registration screen directly.
     */
    public function registerView(): View
    {
        $config = config('porsche');
        $registrationId = session('porsche_registration_id');
        $registration = $registrationId ? PorscheRegistration::find($registrationId) : null;
        $initialStep = 'registration';

        return view('porsche.index', compact('config', 'registration', 'initialStep'));
    }

    /**
     * Handle visitor registration.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|min:2|max:255',
            'contact_number' => ['required', 'string', 'min:8', 'max:20', 'regex:/^[0-9+\s\-()]+$/'],
            'email' => 'required|email|max:255',
            'consent' => 'required|accepted',
        ], [
            'full_name.required' => 'Please enter your full name.',
            'contact_number.required' => 'Please enter your phone number.',
            'contact_number.regex' => 'Please enter a valid phone number.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'consent.accepted' => 'You must agree to the privacy policy to continue.',
        ]);

        $registration = $this->quizService->registerUser($validated);

        session(['porsche_registration_id' => $registration->id]);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'registration' => [
                'id' => $registration->id,
                'full_name' => $registration->full_name,
                'email' => $registration->email,
            ],
        ]);
    }

    /**
     * Submit quiz answers and calculate personalized result.
     */
    public function submitQuiz(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'answers' => 'required|array|size:4',
            'answers.*' => 'required|string|in:A,B,C,a,b,c',
            'registration_id' => 'nullable|integer|exists:porsche_registrations,id',
        ]);

        $registrationId = $validated['registration_id']
            ?? session('porsche_registration_id');

        if (!$registrationId) {
            return response()->json([
                'success' => false,
                'message' => 'Registration is required before completing the quiz.',
            ], 422);
        }

        $attempt = $this->quizService->recordAttempt($registrationId, $validated['answers']);

        $resultConfig = config("porsche.results.{$attempt->result_type}");

        return response()->json([
            'success' => true,
            'attempt_id' => $attempt->id,
            'result_type' => $attempt->result_type,
            'scores' => [
                'A' => $attempt->score_a,
                'B' => $attempt->score_b,
                'C' => $attempt->score_c,
            ],
            'result' => $resultConfig,
        ]);
    }

    /**
     * Optional Campaign Reporting Dashboard.
     */
    public function admin(): View
    {
        $totalRegistrations = PorscheRegistration::count();
        $totalAttempts = PorscheQuizAttempt::count();
        $resultCounts = [
            'A' => PorscheQuizAttempt::where('result_type', 'A')->count(),
            'B' => PorscheQuizAttempt::where('result_type', 'B')->count(),
            'C' => PorscheQuizAttempt::where('result_type', 'C')->count(),
        ];

        $recentRegistrations = PorscheRegistration::with('quizAttempts')
            ->latest()
            ->paginate(20);

        return view('porsche.admin', compact(
            'totalRegistrations',
            'totalAttempts',
            'resultCounts',
            'recentRegistrations'
        ));
    }

    /**
     * Export campaign registrations and quiz attempts as CSV.
     */
    public function export(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="porsche_cayenne_campaign_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Registration ID',
                'Full Name',
                'Contact Number',
                'Email',
                'Consent Given',
                'Registered At',
                'Total Quiz Attempts',
                'Latest Result',
                'Latest Q1',
                'Latest Q2',
                'Latest Q3',
                'Latest Q4',
            ]);

            PorscheRegistration::with(['quizAttempts' => fn($q) => $q->latest()])
                ->chunk(200, function ($registrations) use ($handle) {
                    foreach ($registrations as $r) {
                        $latest = $r->quizAttempts->first();
                        fputcsv($handle, [
                            $r->id,
                            $r->full_name,
                            $r->contact_number,
                            $r->email,
                            $r->consent_given ? 'Yes' : 'No',
                            $r->registered_at?->format('Y-m-d H:i:s'),
                            $r->quizAttempts->count(),
                            $latest?->result_type ?? 'N/A',
                            $latest?->question_1_answer ?? '',
                            $latest?->question_2_answer ?? '',
                            $latest?->question_3_answer ?? '',
                            $latest?->question_4_answer ?? '',
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }
}
