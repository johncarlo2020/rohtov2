@extends('layouts.admin')

@section('content')
    <style>
        .big-checkbox {
            transform: scale(1.5);
            /* Increase the size of the checkbox */
        }

        .stripe-li:nth-child(even) {
            background-color: #f2f2f2;
            /* Even rows background color */
        }

        .stripe-li:nth-child(odd) {
            background-color: #ffffff;
            /* Odd rows background color */
        }
    </style>
    <div class="row">
        <div class="py-4 container-fluid">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="shadow-lg card ">
                        <div class="card-body">
                            <div class="row gx-4">
                                <div class="col-auto">
                                    <div class="">
                                        <i class="fa-solid fa-user" style="font-size: 3rem;"></i>
                                    </div>
                                </div>
                                <div class="col-auto my-auto">
                                    <div class="h-100">
                                        <h5 class="mb-1">
                                            {{ $user->fname }} {{ $user->lname }}
                                        </h5>
                                        <p class="mb-0 text-sm font-weight-bold">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="header d-flex justify-content-between align-items-center mb-3">
                                <p class="text-sm text-uppercase">User Information</p>
                                <button id="editBtn" class="btn btn-secondary btn-sm px-3"><i
                                        class="fa-solid fa-pen-to-square"></i> Edit</button>
                            </div>
                            <form id="userForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">First Name</label>
                                            <input class="form-control" type="text" disabled value="{{ $user->fname }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Last Name</label>
                                            <input class="form-control" type="text" disabled value="{{ $user->lname }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Email Address</label>
                                            <input id="emailInput" class="form-control" type="email" disabled
                                                value="{{ $user->email }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Phone Number</label>
                                            <input class="form-control" type="text" disabled value="{{ $user->number }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    <label for="allianceBankRadio" class="form-control-label">Alliance Bank</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input id="allianceBankYes" name="allianceBank" class="form-check-input" type="radio" disabled value="1" {{ $user->alliance_bank ? 'checked' : '' }}>
                                            <label class="form-check-label" for="allianceBankYes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input id="allianceBankNo" name="allianceBank" class="form-check-input" type="radio" disabled value="0" {{ !$user->alliance_bank ? 'checked' : '' }}>
                                            <label class="form-check-label" for="allianceBankNo">No</label>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" id="submitBtn"
                                        class="btn-success btn ml-auto d-none">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="p-3 pb-0 card-header">
                            <h6 class="mb-0">Stations</h6>
                        </div>
                        <div class="p-3 card-body">
                            <ul class="list-group">
                                @foreach ($user['stations'] as $station)
                                    <li
                                        class="mb-2 border-0 list-group-item stripe-li d-flex justify-content-between ps-0 ">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-sm text-dark"></h6>
                                                <h6 class="mb-1 text-sm text-dark">#{{ $station['id'] }}
                                                    {{ $station['name'] }}</h6>
                                                <span class="text-xs">Average Time : <span
                                                        class="font-weight-bold">{{ $station['time_spent'] }}
                                                        minutes</span></span>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="checkbox" data-id="{{ $station['id'] }}"
                                                id="station_checkbox_{{ $station['id'] }}" class="big-checkbox"
                                                {{ $station['value'] ? 'checked' : '' }}>
                                        </div>
                                    </li>
                                @endforeach
                                <li
                                    class="mb-2 border-0 list-group-item d-flex justify-content-between ps-0 border-radius-lg">
                                    <div class="d-flex align-items-center">

                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-sm text-dark">Total Minutes</h6>
                                            <span class="text-xs"> <span class="font-weight-bold">{{ $totalMinutes }}
                                                    minutes</span></span>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Success Modal --}}
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    User email updated successfully!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    {{-- Include Bootstrap JS for modal functionality if not already included --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script>
        var permissionName = "{{ $permission }}";
        if (permissionName === 'full') {
            $('#editBtn').click(function() {
                $('#emailInput').prop('disabled', false);
                $('#submitBtn').removeClass('d-none');
                $('input[name="allianceBank"]').prop('disabled', false); // Enable radio buttons
            });

            $('#submitBtn').click(function() {
                var userId = {{ $user->id }};
                var email = $('#emailInput').val();
                var allianceBank = $('input[name="allianceBank"]:checked').val(); // Get selected radio value
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ route('editUser') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        id: userId,
                        email: email,
                        alliance_bank: allianceBank // Include allianceBank in the request
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#emailInput').prop('disabled', true);
                            $('#submitBtn').addClass('d-none');
                            $('input[name="allianceBank"]').prop('disabled', true); // Disable radio buttons again
                            toastr.success('User details updated successfully!');
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred: ' + error);
                    }
                });
            });

            $('.big-checkbox').change(function() {
                var newState = $(this).prop('checked');
                var user_id = {{ $user->id }};
                var station_id = $(this).data('id');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ route('check') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: {
                        user_id: user_id,
                        station_id: station_id
                    },
                    success: function(response) {
                        toastr.success('Station checkbox updated successfully!');
                    },
                    error: function(xhr, status, error) {
                        toastr.error('An error occurred while updating the checkbox.');
                    }
                });
            });
        } else {
            // Disable all input elements if permission is not 'full'
            $('input').prop('disabled', true);
        }
    </script>
@endsection
