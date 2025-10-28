@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">User Gifts - Station 3</h4>
                                <p class="text-muted mb-0">Track user gift selections and redemptions</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.gifts') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-gift me-2"></i>Manage Gifts
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Gift</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Station</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Selected At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($userGifts as $userGift)
                                        <tr>
                                            <td>
                                                <div class="px-3">
                                                    <h6 class="mb-0 text-sm">{{ $userGift->id }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="px-3">
                                                    <h6 class="mb-0 text-sm"> {{ $userGift->user->name ?? '' }}</h6>
                                                    <p class="text-xs text-secondary mb-0">ID: {{ $userGift->user_id }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="px-3">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $userGift->user->email ?? 'N/A' }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="px-3">
                                                    <h6 class="mb-0 text-sm">{{ $userGift->gift->name ?? 'Gift ID: ' . $userGift->gift_id }}</h6>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-sm bg-gradient-info">Station {{ $userGift->station_id }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($userGift->is_redeemed ?? false)
                                                    <span class="badge badge-sm bg-gradient-success">
                                                        <i class="fas fa-check me-1"></i>Redeemed
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-warning">
                                                        <i class="fas fa-clock me-1"></i>Selected
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="px-3">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $userGift->created_at->format('M d, Y') }}</p>
                                                    <p class="text-xs text-secondary mb-0">{{ $userGift->created_at->format('H:i') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-gift fa-2x mb-3"></i>
                                                    <p class="mb-0">No user gifts found</p>
                                                    <small>User gift selections will appear here when users select gifts at Station 3</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $userGifts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Dashboard -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-start">
                    <a href="{{ route('admin') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
