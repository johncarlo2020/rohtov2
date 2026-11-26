@extends('layouts.admin')

@section('content')
<style>
    .customer-table-scroll {
        max-height: min(55vh, 36rem);
        overflow: auto;
        overscroll-behavior: contain;
    }

    #customer-table thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: var(--bs-body-bg, #fff);
    }
</style>
<div class="row pt-2 mt-4">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Customers</p>
                            <h5 class="font-weight-bolder">
                                {{ $data['usersCount'] }}
                            </h5>
                            {{-- <p class="mb-0">
                                <span class="text-success text-sm font-weight-bolder">+55%</span>
                                since yesterday
                            </p> --}}
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
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Today's Customer</p>
                            <h5 class="font-weight-bolder">
                                {{ $data['userToday'] }}
                            </h5>
                            {{-- <p class="mb-0">
                                <span class="text-success text-sm font-weight-bolder">+3%</span>
                                since last week
                            </p> --}}
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                            <i class="fa-solid fa-calendar-day text-lg opacity-10" aria-hidden="true"></i>
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
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Completion Rate</p>
                            <h5 class="font-weight-bolder">
                                {{ $data['percentage'] }}%
                            </h5>

                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                            <i class="fa-solid fa-percent text-lg opacity-10" aria-hidden="true"></i>
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
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Customers Finished</p>
                            <h5 class="font-weight-bolder">
                                {{ $data['completedUsers'] }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                            <i class="fa-solid fa-circle-check text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mt-4 row">
    <div class="mb-4 col-lg-12 mb-lg-0">
        <div class="card table-card py-3">
            @if(auth()->check() && auth()->user()->isSuperAdmin())
                <div class="px-4 pt-2 pb-2 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">User Management</h5>
                    <button type="button" class="btn btn-primary bg-gradient-warning text-white fw-bold mb-0" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="fas fa-user-plus me-1"></i> Create User / Staff
                    </button>
                </div>
                <hr class="horizontal dark my-2">
            @endif
            <div class="p-3 px-4">
                <form method="GET" action="{{ route('users') }}" class="d-flex flex-wrap align-items-end gap-2 mb-3" role="search">
                    <input type="hidden" name="sort" value="{{ $queryParameters['sort'] }}">
                    <input type="hidden" name="direction" value="{{ $queryParameters['direction'] }}">
                    <div>
                        <label class="form-label" for="user-search">Search users</label>
                        <input class="form-control" id="user-search" type="search" name="q" maxlength="200"
                               value="{{ $queryParameters['q'] }}" placeholder="Name, email, phone or ID">
                    </div>
                    <div>
                        <label class="form-label" for="registered-from">Registered from</label>
                        <input class="form-control" id="registered-from" type="date" name="from" value="{{ $queryParameters['from'] }}">
                    </div>
                    <div>
                        <label class="form-label" for="registered-to">Registered to</label>
                        <input class="form-control" id="registered-to" type="date" name="to" value="{{ $queryParameters['to'] }}">
                    </div>
                    <div>
                        <label class="form-label" for="users-per-page">Rows per page</label>
                        <select class="form-select" id="users-per-page" name="per_page">
                            @foreach ($pageSizes as $pageSize)
                                <option value="{{ $pageSize }}" @selected($queryParameters['per_page'] === $pageSize)>{{ $pageSize }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-primary mb-0" type="submit">Apply</button>
                    <a class="btn btn-outline-secondary mb-0" href="{{ route('users', array_merge($queryParameters, ['from' => '', 'to' => ''])) }}">All dates</a>
                </form>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button class="btn btn-outline-info mb-0" type="button" data-bs-toggle="modal" data-bs-target="#exportModal" data-export-format="csv">CSV</button>
                    <button class="btn btn-outline-success mb-0" type="button" data-bs-toggle="modal" data-bs-target="#exportModal" data-export-format="xlsx">Excel</button>
                </div>
                @php
                    $columns = ['id' => 'ID', 'name' => 'Full Name', 'email' => 'Email Address', 'created_at' => 'Registration Timestamp'];
                    foreach ($data['stations'] as $id => $name) {
                        $columns['station_' . $id] = strtoupper($name);
                    }
                @endphp
                <div class="table-responsive customer-table-scroll mb-3" role="region" aria-label="Users table" tabindex="0">
                <table id="customer-table" class="table table-hover align-middle border text-nowrap mb-0">
                    <thead>
                        <tr>
                            @foreach ($columns as $column => $label)
                                @php
                                    $isSorted = $queryParameters['sort'] === $column;
                                    $direction = $isSorted && $queryParameters['direction'] === 'asc' ? 'desc' : 'asc';
                                @endphp
                                <th scope="col" @if($isSorted) aria-sort="{{ $queryParameters['direction'] === 'asc' ? 'ascending' : 'descending' }}" @endif>
                                    <a href="{{ route('users', array_merge($queryParameters, ['sort' => $column, 'direction' => $direction])) }}">
                                        {{ $label }}
                                        @if($isSorted)<span aria-hidden="true">{{ $direction === 'desc' ? '↑' : '↓' }}</span>@endif
                                    </a>
                                </th>
                            @endforeach
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data['users'] as $user)
                        @php($completedStations = $data['hasStationUsers'] ? $user->stationUser->pluck('station_id')->all() : [])
                        <tr data-user-url="{{ route('userData', ['user' => $user->id]) }}">
                            <td>{{ $user->id }}</td>
                            <td class="font-weight-bold">
                                <a href="{{ route('userData', ['user' => $user->id]) }}">
                                    {{ trim(($user->title ? $user->title . ' ' : '') . $user->fname . ' ' . $user->lname) ?: $user->fname }}
                                </a>
                                @if($user->hasRole('staff'))
                                    <span class="badge bg-info text-white ms-1" style="font-size: 10px; font-weight: 600;">STAFF</span>
                                @endif
                                @if(!$user->otp_verified && $user->otp)
                                    <span class="badge bg-warning text-dark ms-1" style="font-size: 10px; font-weight: 600;" title="Pending OTP: {{ $user->otp }}">OTP: {{ $user->otp }}</span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at?->toDayDateTimeString() }}</td>
                            @foreach ($data['stations'] as $id => $name)
                                <td class="text-center font-weight-bold">
                                    @if(in_array($id, $completedStations))
                                        <span class="badge bg-success text-white px-2 py-1" style="font-size: 11px; font-weight: 600;">Yes</span>
                                    @else
                                        <span class="badge bg-secondary text-white px-2 py-1" style="font-size: 11px; font-weight: 600;">No</span>
                                    @endif
                                </td>
                            @endforeach
                            <td class="button-delete text-center">
                                @if($user->isProtectedAdmin())
                                    <button class="btn btn-secondary btn-sm btn-protected" disabled 
                                            title="This admin user is protected and cannot be deleted"
                                            data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="fa fa-lock"></i> Protected
                                    </button>
                                @else
                                    <button class="btn btn-danger btn-sm delete-user-btn" data-delete-url="{{ route('users.destroy', ['id' => $user->id]) }}"
                                        data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-user-name="{{ $user->fname }}">Delete</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="{{ count($columns) + 1 }}" class="text-center py-4">No users match these filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                {{ $data['users']->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="deleteUserForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-white text-white">
                    <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="deleteUserName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel">
    <div class="modal-dialog">
        <form class="modal-content" method="GET" action="{{ route('users') }}">
            @foreach ($queryParameters as $name => $value)
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
            @endforeach
            <input type="hidden" name="export" id="exportFormat">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">Name Export File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="exportFileName" class="form-label">File name</label>
                <input type="text" name="filename" id="exportFileName" class="form-control" value="users" maxlength="100" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Export</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('exportModal').addEventListener('show.bs.modal', function (event) {
        document.getElementById('exportFormat').value = event.relatedTarget.dataset.exportFormat;
    });

    document.getElementById('deleteUserModal').addEventListener('show.bs.modal', function (event) {
        document.getElementById('deleteUserForm').action = event.relatedTarget.dataset.deleteUrl;
        document.getElementById('deleteUserName').textContent = event.relatedTarget.dataset.userName;
    });

    document.getElementById('customer-table').addEventListener('click', function (event) {
        const row = event.target.closest('tr[data-user-url]');
        if (row && !event.target.closest('a, button, .button-delete')) {
            window.location.assign(row.dataset.userUrl);
        }
    });
</script>

@if(session('success'))
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div id="successToast" class="toast align-items-center bg-success text-white border-0 fade show" role="alert"
        aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <i class="fa fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toastEl = document.getElementById('successToast');
        if (toastEl) {
            var toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();
        }
    });
</script>
@endif

@if(session('error'))
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div id="errorToast" class="toast align-items-center bg-danger text-white border-0 fade show" role="alert"
        aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <i class="fa fa-exclamation-circle me-2"></i>
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toastEl = document.getElementById('errorToast');
        if (toastEl) {
            var toast = new bootstrap.Toast(toastEl, { delay: 5000 });
            toast.show();
        }
    });
</script>
@endif

@if(auth()->check() && auth()->user()->isSuperAdmin())
<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-dark text-white">
                <h5 class="modal-title text-white font-weight-bold" id="createUserModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Create New System User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-dark">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="fname" class="form-control" required placeholder="John">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-dark">Last Name</label>
                            <input type="text" name="lname" class="form-control" placeholder="Doe">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-dark">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required placeholder="user@example.com">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-dark">Assigned Role</label>
                            <input type="text" class="form-control bg-light" value="Staff" readonly disabled>
                            <input type="hidden" name="role" value="staff">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label font-weight-bold text-xs text-uppercase text-dark">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="6" placeholder="At least 6 characters">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4">
                    <button type="button" class="btn btn-secondary mb-0" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-white fw-bold mb-0">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
