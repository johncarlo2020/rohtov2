@extends('layouts.admin')

@section('content')
<div class="py-3">
    @if(session('success'))
        <div class="alert alert-success text-white">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger text-white">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header pb-0"><h6>Add Booking Date</h6></div>
        <div class="card-body">
            <form method="POST" action="{{ route('booking-dates.store') }}" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Capacity</label>
                    <input type="number" name="capacity" min="0" class="form-control" value="{{ old('capacity', 0) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Reason / Note</label>
                    <input type="text" name="reason" class="form-control" value="{{ old('reason') }}">
                </div>
                <div class="col-md-1">
                    <div class="form-check">
                        <input type="checkbox" name="is_available" value="1" class="form-check-input" id="newAvail" checked>
                        <label class="form-check-label" for="newAvail">Open</label>
                    </div>
                </div>
                <div class="col-md-2"><button class="btn btn-primary w-100 mb-0">Add</button></div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header pb-0"><h6>Booking Dates</h6></div>
        <div class="card-body table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr><th>Date</th><th>Status</th><th>Capacity</th><th>Booked</th><th>Remaining</th><th>Reason</th><th class="text-end">Actions</th></tr>
                </thead>
                <tbody>
                @forelse($dates as $d)
                    <tr>
                        <td>{{ $d->display_date }}</td>
                        <td><span class="badge bg-{{ $d->is_available ? 'success' : 'secondary' }}">{{ $d->is_available ? 'Open' : 'Closed' }}</span></td>
                        <td>{{ $d->capacity }}</td>
                        <td>{{ $d->bookings_count }}</td>
                        <td>{{ max(0, $d->capacity - $d->bookings_count) }}</td>
                        <td>{{ $d->reason }}</td>
                        <td class="text-end">
                            <a href="{{ route('booking-dates.show', $d) }}" class="btn btn-sm btn-outline-primary mb-0">Bookings</a>
                            <form method="POST" action="{{ route('booking-dates.destroy', $d) }}" class="d-inline" onsubmit="return confirm('Delete this date?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger mb-0">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">No booking dates yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
