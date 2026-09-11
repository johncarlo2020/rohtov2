@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="font-weight-bolder mb-1">System History Logs</h4>
            <p class="text-sm text-muted mb-0">Audit trail of all booking creations, modifications, cancellations, attendance verification, and user management.</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="card mb-4 border shadow-sm">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('history.logs') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="q" class="form-control" placeholder="Search by name, role, action, or description..." value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="action" class="form-select" onchange="this.form.submit()">
                        <option value="">All Action Types</option>
                        @foreach($actionTypes as $type)
                            <option value="{{ $type }}" {{ request('action') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-warning w-100 mb-0">Filter</button>
                    @if(request()->hasAny(['q', 'action', 'date']))
                        <a href="{{ route('history.logs') }}" class="btn btn-outline-secondary mb-0"><i class="fas fa-times"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- History Logs Table -->
    <div class="card border shadow-sm">
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Timestamp</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Performed By</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Action</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Description</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="ps-4 text-xs font-weight-bold text-dark">
                                    <i class="far fa-clock me-1 text-muted"></i>
                                    {{ $log->created_at->format('M d, Y') }}<br>
                                    <span class="text-muted text-xxs">{{ $log->created_at->format('h:i:s A') }}</span>
                                </td>
                                <td class="ps-3">
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-0 text-sm font-weight-bold">{{ $log->user_name ?? 'System' }}</h6>
                                        <span class="badge bg-gradient-dark text-xxs text-uppercase w-auto mt-1 d-inline-block" style="width: fit-content;">
                                            {{ $log->user_role ?? 'System' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="ps-3">
                                    @php
                                        $badgeClass = 'bg-secondary';
                                        if (str_contains($log->action, 'CREATE')) $badgeClass = 'bg-success';
                                        elseif (str_contains($log->action, 'MODIFY')) $badgeClass = 'bg-warning text-dark';
                                        elseif (str_contains($log->action, 'DELETE') || str_contains($log->action, 'CANCEL')) $badgeClass = 'bg-danger';
                                        elseif (str_contains($log->action, 'ATTENDED')) $badgeClass = 'bg-info';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} text-uppercase px-2 py-1">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="ps-3 text-sm text-wrap" style="max-width: 400px;">
                                    <p class="text-xs font-weight-bold text-dark mb-0 line-height-normal">
                                        {{ $log->description }}
                                    </p>
                                </td>
                                <td class="ps-3 text-xs text-muted">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-history fa-2x mb-2 text-secondary"></i><br>
                                    No history log entries found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($logs->hasPages())
                <div class="px-4 pt-3 pb-2 d-flex justify-content-end">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
