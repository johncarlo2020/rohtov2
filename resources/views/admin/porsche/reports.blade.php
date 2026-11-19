@extends('layouts.admin')

@section('content')
<div class="row pt-2">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Participants</p>
                            <h5 class="font-weight-bolder">{{ number_format($totalParticipants) }}</h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                            <i class="fa-solid fa-user text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Completed Quizzes</p>
                            <h5 class="font-weight-bolder">{{ number_format($totalEntries) }}</h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-dark shadow-dark text-center rounded-circle">
                            <i class="fa-solid fa-flag-checkered text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Avg Attempts / User</p>
                            <h5 class="font-weight-bolder">{{ $avgEntries }}</h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                            <i class="fa-solid fa-chart-line text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            @php
                                $topResult = 'A';
                                $topVal = $countA;
                                if ($countB > $topVal) { $topResult = 'B'; $topVal = $countB; }
                                if ($countC > $topVal) { $topResult = 'C'; $topVal = $countC; }
                            @endphp
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Top Result</p>
                            <h5 class="font-weight-bolder">Result {{ $topResult }}</h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-dark shadow-dark text-center rounded-circle">
                            <i class="fa-solid fa-circle-check text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Export Action Cards (2-Color Theme) --}}
<div class="row mt-4">
    {{-- Participants Export Card --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon icon-shape bg-gradient-dark shadow-dark text-center rounded-circle me-3">
                        <i class="fa-solid fa-users text-white opacity-10"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-primary">Participant Registrations Export</h6>
                        <small class="text-xs text-muted">Download participant records, PDPA consent, and entry totals</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <form method="GET" action="{{ route('admin.reports.export.participants') }}">
                    <div class="row g-2 mb-2">
                        <div class="col-sm-6">
                            <label class="form-control-label text-sm">Date From</label>
                            <input type="date" name="date_from" id="reportPartDateFrom" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-control-label text-sm">Date To</label>
                            <input type="date" name="date_to" id="reportPartDateTo" class="form-control">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2" onclick="document.getElementById('reportPartDateFrom').value=new Date().toISOString().split('T')[0];document.getElementById('reportPartDateTo').value=new Date().toISOString().split('T')[0];">Today</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2" onclick="document.getElementById('reportPartDateFrom').value='';document.getElementById('reportPartDateTo').value='';">All Dates</button>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-0">
                        <i class="fa fa-file-csv me-1"></i> Download Participants CSV
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Quiz Entries Export Card --}}
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon icon-shape bg-gradient-dark shadow-dark text-center rounded-circle me-3">
                        <i class="fa-solid fa-list-check text-white opacity-10"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-dark">Completed Quiz Entries Export</h6>
                        <small class="text-xs text-muted">Download detailed attempt answers, personality scores, and outcomes</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <form method="GET" action="{{ route('admin.reports.export.entries') }}">
                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <label class="form-control-label text-sm">Result Type Filter</label>
                            <select name="result_type" class="form-select">
                                <option value="">All Results (A, B, C)</option>
                                <option value="A">Result A – Endless Possibilities</option>
                                <option value="B">Result B – Unforgettable Experience</option>
                                <option value="C">Result C – Memory Worth Keeping</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-control-label text-sm">Date From</label>
                            <input type="date" name="date_from" id="reportEntryDateFrom" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-control-label text-sm">Date To</label>
                            <input type="date" name="date_to" id="reportEntryDateTo" class="form-control">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2" onclick="document.getElementById('reportEntryDateFrom').value=new Date().toISOString().split('T')[0];document.getElementById('reportEntryDateTo').value=new Date().toISOString().split('T')[0];">Today</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary py-1 px-2" onclick="document.getElementById('reportEntryDateFrom').value='';document.getElementById('reportEntryDateTo').value='';">All Dates</button>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 mb-0">
                        <i class="fa fa-file-csv me-1"></i> Download Quiz Entries CSV
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
