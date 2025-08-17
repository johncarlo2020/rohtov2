@extends('layouts.admin')

@section('content')
    <div class="mt-4 row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Charm Configuration</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="p-4">
                        <form id="charmConfigForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="charm_count" class="form-control-label">Charm Count</label>
                                        <input class="form-control" type="number" id="charm_count" name="charm_count"
                                               value="{{ $charmConfig->charm_count ?? 0 }}" min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="is_enabled" class="form-control-label">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_enabled" name="is_enabled"
                                                   {{ ($charmConfig->is_enabled ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_enabled">
                                                Enabled
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update Configuration</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configure toastr
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        }

        document.getElementById('charmConfigForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Add the checkbox value properly - if unchecked, it won't be in FormData
            if (!formData.has('is_enabled')) {
                formData.append('is_enabled', '0');
            } else {
                formData.set('is_enabled', '1');
            }

            fetch('{{ route("charmConfig.update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (typeof toastr !== 'undefined' && typeof toastr.success === 'function') {
                        toastr.success(data.message || 'Configuration updated successfully!');
                    } else {
                        alert('Configuration updated successfully!');
                    }
                } else {
                    if (typeof toastr !== 'undefined' && typeof toastr.error === 'function') {
                        toastr.error(data.message || 'Error updating configuration');
                    } else {
                        alert('Error updating configuration');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (typeof toastr !== 'undefined' && typeof toastr.error === 'function') {
                    toastr.error('Error updating configuration');
                } else {
                    alert('Error updating configuration');
                }
            });
        });
    </script>
@endsection
