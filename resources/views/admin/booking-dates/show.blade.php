@extends('layouts.admin')

@section('content')
<div class="py-3">
    <a href="{{ route('booking-dates.index') }}" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa-solid fa-arrow-left me-1"></i>All Dates</a>

    @if(session('success'))
        <div class="alert alert-success text-white">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger text-white">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header pb-0"><h6>{{ $date->display_date }}</h6></div>
        <div class="card-body">
            <form method="POST" action="{{ route('booking-dates.update', $date) }}" class="row g-3 align-items-end">
                @csrf @method('PUT')
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $date->date->format('Y-m-d') }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" min="0" class="form-control" value="{{ $date->capacity }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reason / Note</label>
                    <input type="text" name="reason" class="form-control" value="{{ $date->reason }}">
                </div>
                <div class="col-md-1">
                    <div class="form-check">
                        <input type="checkbox" name="is_available" value="1" class="form-check-input" id="dAvail" @checked($date->is_available)>
                        <label class="form-check-label" for="dAvail">Open</label>
                    </div>
                </div>
                <div class="col-md-2"><button class="btn btn-primary w-100 mb-0">Save</button></div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header pb-0"><h6>User Bookings ({{ $bookings->count() }})</h6></div>
        <div class="card-body table-responsive">
            <table class="table align-items-center mb-0">
                <thead><tr><th>Ref</th><th>Name</th><th>Email</th><th>Phone</th><th>Venue</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @forelse($bookings as $b)
                    <tr>
                        <td>{{ $b->reference_no }}</td>
                        <td>{{ $b->customer_name }}</td>
                        <td>{{ $b->customer_email }}</td>
                        <td>{{ $b->customer_phone }}</td>
                        <td>{{ $b->venue }}</td>
                        <td>{{ ucfirst($b->status) }}</td>
                        <td>
                            @if($b->user)
                                <a href="{{ route('userData', $b->user->id) }}" class="btn btn-sm btn-outline-primary mb-0">User</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">No bookings for this date.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
