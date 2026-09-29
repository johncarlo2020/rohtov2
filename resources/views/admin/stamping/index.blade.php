@extends('layouts.admin')

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Add Stamp</h5>
                    <form method="POST" action="{{ route('admin.stamping.store') }}" class="row g-3 align-items-end">
                        @csrf
                        <div class="col-lg-4 col-md-6">
                            <label for="new-user" class="form-label">Customer</label>
                            <select id="new-user" name="user_id" class="form-select" required>
                                <option value="">Select customer</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                                        {{ $user->name }}{{ $user->code ? ' (' . $user->code . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="new-station" class="form-label">Station</label>
                            <select id="new-station" name="station_id" class="form-select" required>
                                <option value="">Select station</option>
                                @foreach ($stations as $station)
                                    <option value="{{ $station->id }}" @selected(old('station_id') == $station->id)>
                                        {{ $station->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="new-time-spent" class="form-label">Time spent (seconds)</label>
                            <input id="new-time-spent" name="time_spent" type="number" min="0" step="1"
                                value="{{ old('time_spent') }}" class="form-control">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <button type="submit" class="btn btn-primary mb-0 w-100">
                                <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Add stamp
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Stamp Records</h5>
                        <span class="text-sm text-secondary">{{ $stamps->total() }} records</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Station</th>
                                    <th>Time (seconds)</th>
                                    <th>Stamped at</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stamps as $stamp)
                                    <tr>
                                        <td>
                                            <select name="user_id" form="update-stamp-{{ $stamp->id }}" class="form-select" required>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}" @selected($stamp->user_id == $user->id)>
                                                        {{ $user->name }}{{ $user->code ? ' (' . $user->code . ')' : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="station_id" form="update-stamp-{{ $stamp->id }}" class="form-select" required>
                                                @foreach ($stations as $station)
                                                    <option value="{{ $station->id }}" @selected($stamp->station_id == $station->id)>
                                                        {{ $station->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input name="time_spent" type="number" min="0" step="1"
                                                value="{{ $stamp->time_spent }}" form="update-stamp-{{ $stamp->id }}"
                                                class="form-control" aria-label="Time spent in seconds">
                                        </td>
                                        <td>{{ optional($stamp->created_at)->format('M j, Y H:i') }}</td>
                                        <td class="text-end text-nowrap">
                                            <form id="update-stamp-{{ $stamp->id }}" method="POST"
                                                action="{{ route('admin.stamping.update', $stamp) }}">
                                                @csrf
                                                @method('PUT')
                                            </form>
                                            <button type="submit" form="update-stamp-{{ $stamp->id }}"
                                                class="btn btn-sm btn-primary mb-0" title="Save stamp">
                                                <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.stamping.destroy', $stamp) }}"
                                                class="d-inline" onsubmit="return confirm('Delete this stamp record?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger mb-0" title="Delete stamp">
                                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-4">No stamp records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $stamps->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection