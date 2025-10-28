@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0">Gifts Management</h4>
                                <p class="text-muted mb-0">Manage and monitor gift availability and status</p>
                            </div>
                            <div>
                                <a href="{{ route('admin.user.gifts') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list me-2"></i>View User Gifts
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Gifts</p>
                                    <h5 class="font-weight-bolder text-primary">
                                        {{ $stats['total_gifts'] }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="fas fa-gift text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Enabled Gifts</p>
                                    <h5 class="font-weight-bolder text-success">
                                        {{ $stats['enabled_gifts'] }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="fas fa-check-circle text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Disabled Gifts</p>
                                    <h5 class="font-weight-bolder text-warning">
                                        {{ $stats['disabled_gifts'] }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="fas fa-times-circle text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Selected</p>
                                    <h5 class="font-weight-bolder text-info">
                                        {{ $stats['total_selected'] }}
                                    </h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                    <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Gifts Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">All Gifts</h6>
                        <div class="text-muted">
                            <small>Manage gift availability and monitor selection statistics</small>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-3">
                            <table class="table table-hover align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Gift Name</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Total Available</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Selected Count</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Remaining</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Actions</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($gifts as $gift)
                                        <tr>
                                            <td>
                                                <div class="px-3">
                                                    <h6 class="mb-0 text-sm">{{ $gift->id }}</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="px-3">
                                                    <h6 class="mb-0 text-sm font-weight-bold">{{ $gift->name }}</h6>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-sm bg-gradient-info">{{ $gift->total ?? 'N/A' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-sm bg-gradient-primary">{{ $gift->user_gifts_count }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $remaining = $gift->total ? max(0, $gift->total - $gift->user_gifts_count) : 'N/A';
                                                    $badgeClass = $remaining === 0 ? 'bg-gradient-danger' : 'bg-gradient-success';
                                                @endphp
                                                <span class="badge badge-sm {{ $badgeClass }}">{{ $remaining }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($gift->enabled)
                                                    <span class="badge badge-sm bg-gradient-success">
                                                        <i class="fas fa-check me-1"></i>Enabled
                                                    </span>
                                                @else
                                                    <span class="badge badge-sm bg-gradient-secondary">
                                                        <i class="fas fa-times me-1"></i>Disabled
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('admin.gifts.toggle', $gift) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @if($gift->enabled)
                                                        <button type="submit" class="btn btn-sm btn-outline-warning"
                                                                onclick="return confirm('Are you sure you want to disable this gift?')"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Disable Gift">
                                                            <i class="fas fa-ban"></i> Disable
                                                        </button>
                                                    @else
                                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                                onclick="return confirm('Are you sure you want to enable this gift?')"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Enable Gift">
                                                            <i class="fas fa-check"></i> Enable
                                                        </button>
                                                    @endif
                                                </form>
                                            </td>
                                            <td>
                                                <div class="px-3">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $gift->created_at->format('M d, Y') }}</p>
                                                    <p class="text-xs text-secondary mb-0">{{ $gift->created_at->format('H:i') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-gift fa-2x mb-3"></i>
                                                    <p class="mb-0">No gifts found</p>
                                                    <small>Gifts will appear here once they are added to the system</small>
                                                </div>
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

    <!-- Initialize tooltips -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
