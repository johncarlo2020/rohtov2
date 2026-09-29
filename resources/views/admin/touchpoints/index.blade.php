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


            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Touchpoints</h5>
                        <span class="text-sm text-secondary">{{ $touchpoints->total() }} records</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead>
                                <tr>
                                    <th>Key</th>
                                    <th>Label</th>
                                    <th>Required touches</th>
                                    <th>Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($touchpoints as $touchpoint)
                                    <tr>
                                        <td>
                                            <input name="key" type="text" maxlength="255" value="{{ $touchpoint->key }}"
                                                form="update-touchpoint-{{ $touchpoint->id }}" class="form-control" required>
                                        </td>
                                        <td>
                                            <input name="label" type="text" maxlength="255" value="{{ $touchpoint->label }}"
                                                form="update-touchpoint-{{ $touchpoint->id }}" class="form-control" required>
                                        </td>
                                        <td>
                                            <input name="required_touches" type="number" min="1" max="255" step="1"
                                                value="{{ $touchpoint->required_touches }}"
                                                form="update-touchpoint-{{ $touchpoint->id }}" class="form-control" required>
                                        </td>
                                        <td>{{ optional($touchpoint->updated_at)->format('M j, Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-4">No touchpoints found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $touchpoints->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection