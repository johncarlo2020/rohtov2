@extends('layouts.admin')

@section('content')
<div class="row pt-2 mt-4">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                Please check the form errors below.
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Campaign Configuration</h6>
                <p class="text-xs text-muted mb-0">General rules for quiz submissions and participant registration</p>
            </div>
            <div class="card-body p-3">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-control-label text-sm">Campaign Name</label>
                        <input type="text" name="campaign_name" class="form-control" value="{{ old('campaign_name', $settings['campaign_name']) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-control-label text-sm d-block">Campaign Status</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="campaign_status" id="statusActive" value="active" {{ old('campaign_status', $settings['campaign_status']) === 'active' ? 'checked' : '' }}>
                            <label class="form-check-label text-sm" for="statusActive">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="campaign_status" id="statusPaused" value="paused" {{ old('campaign_status', $settings['campaign_status']) === 'paused' ? 'checked' : '' }}>
                            <label class="form-check-label text-sm" for="statusPaused">Paused</label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check form-switch ps-0">
                            <input type="hidden" name="allow_multiple_entries" value="0">
                            <input class="form-check-input ms-0 me-2" type="checkbox" id="allowMultipleEntries" name="allow_multiple_entries" value="1" {{ old('allow_multiple_entries', $settings['allow_multiple_entries']) ? 'checked' : '' }}>
                            <label class="form-check-label form-control-label text-sm" for="allowMultipleEntries">
                                Allow Multiple Entries per Participant
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-control-label text-sm">Max Entries per Participant (Leave blank for unlimited)</label>
                        <input type="number" min="1" name="max_entries_per_participant" class="form-control" style="max-width: 250px;" value="{{ old('max_entries_per_participant', $settings['max_entries_per_participant']) }}" placeholder="Unlimited">
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-control-label text-sm">Privacy Policy URL</label>
                        <input type="url" name="privacy_policy_url" class="form-control" value="{{ old('privacy_policy_url', $settings['privacy_policy_url']) }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary mb-0">Save Settings</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Campaign Info</h6>
            </div>
            <div class="card-body p-3">
                <p class="text-xs text-muted mb-2">
                    <strong>Porsche Cayenne – Journey Quiz</strong>
                </p>
                <p class="text-xs text-muted mb-3">
                    Operates on <code>PorscheRegistration</code> and <code>PorscheQuizAttempt</code> with 4-question answer scoring.
                </p>
                <a href="{{ route('welcome') }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 mb-0">
                    Preview Public Microsite
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
