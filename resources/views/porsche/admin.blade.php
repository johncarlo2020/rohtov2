<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Porsche Cayenne Campaign Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0b0c0e;
            color: #ffffff;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            padding: 30px 15px;
        }
        .card {
            background-color: #17191e;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 4px;
            color: #ffffff;
        }
        .table {
            color: #ffffff;
        }
        .table-dark {
            --bs-table-bg: #17191e;
            --bs-table-border-color: rgba(255, 255, 255, 0.1);
        }
        .badge-result-a { background-color: #2563eb; }
        .badge-result-b { background-color: #059669; }
        .badge-result-c { background-color: #d97706; }
        .porsche-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 0.85rem;
            padding: 8px 20px;
            border-radius: 2px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }
        .porsche-btn:hover {
            border-color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">PORSCHE CAYENNE CAMPAIGN</h1>
                <p class="text-secondary mb-0">Interactive Journey Quiz Reporting & Analytics</p>
            </div>
            <div>
                <a href="{{ route('users.export') }}" class="porsche-btn">
                    Export CSV
                </a>
                <a href="{{ route('welcome') }}" target="_blank" class="porsche-btn ms-2">
                    View Microsite
                </a>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card p-3">
                    <span class="text-secondary small text-uppercase">Total Registrations</span>
                    <h2 class="h2 mb-0 mt-2">{{ number_format($totalRegistrations) }}</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <span class="text-secondary small text-uppercase">Completed Attempts</span>
                    <h2 class="h2 mb-0 mt-2">{{ number_format($totalAttempts) }}</h2>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card p-3">
                    <span class="text-secondary small text-uppercase">Result A</span>
                    <h2 class="h3 mb-0 mt-2 text-primary">{{ number_format($resultCounts['A']) }}</h2>
                    <small class="text-secondary mt-1">Endless Possibilities</small>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card p-3">
                    <span class="text-secondary small text-uppercase">Result B</span>
                    <h2 class="h3 mb-0 mt-2 text-success">{{ number_format($resultCounts['B']) }}</h2>
                    <small class="text-secondary mt-1">Unforgettable Experience</small>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card p-3">
                    <span class="text-secondary small text-uppercase">Result C</span>
                    <h2 class="h3 mb-0 mt-2 text-warning">{{ number_format($resultCounts['C']) }}</h2>
                    <small class="text-secondary mt-1">Memory Worth Keeping</small>
                </div>
            </div>
        </div>

        <!-- Recent Registrations Table -->
        <div class="card p-4">
            <h5 class="card-title mb-3">Recent Campaign Registrations</h5>
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Consent</th>
                            <th>Attempts</th>
                            <th>Latest Result</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRegistrations as $reg)
                            @php $latestAttempt = $reg->quizAttempts->first(); @endphp
                            <tr>
                                <td>#{{ $reg->id }}</td>
                                <td class="fw-semibold">{{ $reg->full_name }}</td>
                                <td>{{ $reg->contact_number }}</td>
                                <td>{{ $reg->email }}</td>
                                <td>
                                    <span class="badge bg-success">Consent Given</span>
                                </td>
                                <td>{{ $reg->quizAttempts->count() }}</td>
                                <td>
                                    @if($latestAttempt)
                                        <span class="badge badge-result-{{ strtolower($latestAttempt->result_type) }}">
                                            Result {{ $latestAttempt->result_type }}
                                        </span>
                                    @else
                                        <span class="text-secondary">Pending Quiz</span>
                                    @endif
                                </td>
                                <td class="text-secondary">{{ $reg->registered_at?->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-secondary">
                                    No registrations yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $recentRegistrations->links() }}
            </div>
        </div>
    </div>
</body>
</html>
