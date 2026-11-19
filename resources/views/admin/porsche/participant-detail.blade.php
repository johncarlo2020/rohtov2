@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="py-4 container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- Top User Profile Card with shadow-lg matching userData.blade.php --}}
            <div class="col-md-12 mb-3">
                <div class="shadow-lg card">
                    <div class="card-body">
                        <div class="row gx-4">
                            <div class="col-auto">
                                <div>
                                    <i class="fa-solid fa-user" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                            <div class="col-auto my-auto">
                                <div class="h-100">
                                    <h5 class="mb-1">
                                        {{ $participant->full_name }}
                                    </h5>
                                    <p class="mb-0 text-sm font-weight-bold">
                                        {{ $participant->email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Participant Information Form/Card matching userData.blade.php --}}
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="header d-flex justify-content-between align-items-center mb-3">
                            <p class="text-sm text-uppercase font-weight-bold mb-0">Participant Information</p>
                            <a href="{{ route('admin.participants') }}" class="btn btn-secondary btn-sm px-3 mb-0">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label text-sm">Full Name</label>
                                    <input class="form-control" type="text" disabled value="{{ $participant->full_name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label text-sm">Contact Number</label>
                                    <input class="form-control" type="text" disabled value="{{ $participant->contact_number }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label text-sm">Email Address</label>
                                    <input class="form-control" type="email" disabled value="{{ $participant->email }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label text-sm">Registration Timestamp</label>
                                    <input class="form-control" type="text" disabled value="{{ $participant->registered_at?->format('Y-m-d H:i:s') ?? '-' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label text-sm">PDPA Consent</label>
                                    <input class="form-control" type="text" disabled value="{{ $participant->consent_given ? 'Consented (' . $participant->consent_timestamp?->format('Y-m-d H:i:s') . ')' : 'No' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label text-sm">Total Quiz Attempts</label>
                                    <input class="form-control" type="text" disabled value="{{ $participant->quizAttempts->count() }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Completed Quiz History Table --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0 px-3 pt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h6 class="mb-0">Quiz Attempts History</h6>
                        <a href="{{ route('admin.reports.export.entries') }}" class="btn btn-sm btn-outline-dark mb-0 py-1 px-3">
                            <i class="fa fa-download me-1"></i> Export Entries
                        </a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Attempt</th>
                                        <th class="text-center">Answers</th>
                                        <th class="text-center">Scores (A-B-C)</th>
                                        <th class="text-center">Result</th>
                                        <th class="text-center">Completed At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($participant->quizAttempts as $attempt)
                                        <tr>
                                            <td>
                                                <div class="ms-2">
                                                    <span class="badge bg-light text-dark border">Attempt #{{ $attempt->attempt_number }}</span>
                                                    <small class="text-muted d-block text-xxs mt-1">Entry #{{ $attempt->id }}</small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="font-monospace text-xs fw-bold px-2 py-1 bg-gray-100 rounded">
                                                    {{ $attempt->answers_string }}
                                                </span>
                                            </td>
                                            <td class="text-center text-xs">
                                                <span class="badge bg-danger-subtle text-danger border">A: {{ $attempt->score_a }}</span>
                                                <span class="badge bg-warning-subtle text-warning border">B: {{ $attempt->score_b }}</span>
                                                <span class="badge bg-success-subtle text-success border">C: {{ $attempt->score_c }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($attempt->result_type === 'A')
                                                    <span class="badge bg-gradient-danger">Result A</span>
                                                @elseif($attempt->result_type === 'B')
                                                    <span class="badge bg-gradient-warning">Result B</span>
                                                @else
                                                    <span class="badge bg-gradient-success">Result C</span>
                                                @endif
                                                <small class="d-block text-muted text-xs mt-1">{{ $attempt->result_title }}</small>
                                            </td>
                                            <td class="text-center text-xs text-muted">
                                                {{ $attempt->completed_at?->format('Y-m-d H:i') ?? '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted text-sm">
                                                No quiz attempts completed by this participant yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
