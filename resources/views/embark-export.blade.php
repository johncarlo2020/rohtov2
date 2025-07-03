@extends('layouts.admin')

@section('content')
    <style>
        #customer-table tbody tr {
            cursor: pointer;
        }
        .task-image {
            max-width: 50px;
            max-height: 50px;
            margin: 2px;
        }
           .sticky-action {
            position: sticky;
            right: 0;
            background: #f8f8f8;
            z-index: 999;
            box-shadow: -2px 0 5px -2px rgba(0, 0, 0, 0.12);
        }

           th {
            position: sticky !important;
            top: 0;
            background-color: #f8f9fa;
            z-index: 998;
        }

        .custom-table {
            width: 100%;
            margin: 0 !important;
            padding: 0 !important;
            overflow-y: auto !important;
            max-height: 72vh !important;
            margin-top: 20px !important;
            margin-bottom: 20px !important;
            padding-bottom: 20px !important;
        }
    </style>

    <div class="mt-4 row">
        <div class="mb-4 col-lg-12 mb-lg-0">
            <div class="card p-3">
                <div class="p-3 pb-0 card-header">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">Customer Tasks</h6>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <p><strong>Total Users:</strong> {{ $totalUsers }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Users with Tasks:</strong> {{ $usersWithTasks }}</p>
                        </div>
                    </div>
                </div>
                <!-- Loader shown while DataTable initializes -->
                <div id="table-loader" class="text-center my-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <!-- Container hidden until DataTable init completes -->
                <div id="table-container" class="px-2" style="display:none;">
                    <table id="customer-table" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                @if($tasks->isNotEmpty())
                                    @foreach ($tasks as $task)
                                        @if($task->id == 4)
                                        <th>Pledge (Status)</th>
                                        <th>Pledge (Date)</th>
                                        @else
                                        <th>{{ $task->name }} (Status/Image)</th>
                                        <th>{{ $task->name }} (Date)</th>
                                        @endif
                                    @endforeach
                                @endif
                                <th class="sticky-action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data will be populated by DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="taskImageModal" tabindex="-1" aria-labelledby="taskImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Completion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modal-task-image" src="" alt="Task Image" class="img-fluid mb-3">
                    <button id="confirm-completion" class="btn btn-success">Mark as Completed</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Redeem Confirmation Modal -->
    <div class="modal fade" id="redeemConfirmationModal" tabindex="-1" aria-labelledby="redeemConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="redeemConfirmationModalLabel">Confirm Redemption</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to redeem for this user?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmRedeemButton" class="btn btn-success">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables & Plugins -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">


    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script>

        const table = $('#customer-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '{{ route("embark.data") }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'number', name: 'number' },
                { data: 'email', name: 'email' },
                @foreach($tasks as $task)
                    { data: 'task_status_{{ $task->id }}', name: 'task_status_{{ $task->id }}', orderable: false, searchable: false },
                    { data: 'task_date_{{ $task->id }}', name: 'task_date_{{ $task->id }}', orderable: false, searchable: false },
                @endforeach
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'sticky-action' }
            ],
            dom: "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-8 text-center'B><'col-sm-12 col-md-2'f>>" +
                   "<'row'<'col-sm-12 table-responsive my-2 custom-table'tr>>" +
                   "<'d-flex justify-content-between'ip>",
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            order: [[0, 'desc']],
            initComplete: function () {
                // Hide loader and show table container when ready
                $('#table-loader').hide();
                $('#table-container').show();
            }
        });

        $('.dataTables_filter').addClass('float-start');
        $('.dataTables_filter label').addClass('w-100');

        $(document).ready(function () {
            let selectedTaskId = null;
            let selectedUserId = null;

            // Delegate click event to handle images rendered by DataTables
            $('#customer-table tbody').on('click', '.clickable-image', function () {
                selectedTaskId = $(this).data('task-id');
                selectedUserId = $(this).data('user-id');
                $('#modal-task-image').attr('src', $(this).data('image'));
                console.log('Selected Task ID:', selectedTaskId);
                new bootstrap.Modal(document.getElementById('taskImageModal')).show();
            });

            $('#confirm-completion').on('click', function () {
                const formData = new FormData();
                formData.append('user_id', selectedUserId);
                formData.append('task_id', selectedTaskId);

                fetch("{{ route('tasks.complete') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Task update failed.');
                    }
                })
                .catch(error => {
                    console.log('Error:', error);
                    alert('Something went wrong while completing the task.');
                });
            });

            let selectedUserIdForRedeem = null;

            $('#customer-table tbody').on('click', '.redeem-btn', function () {
                selectedUserIdForRedeem = $(this).data('user-id');
                const redeemModal = new bootstrap.Modal(document.getElementById('redeemConfirmationModal'));
                redeemModal.show();
            });

            $('#confirmRedeemButton').on('click', function () {
                if (selectedUserIdForRedeem) {
                    const button = $(`.redeem-btn[data-user-id="${selectedUserIdForRedeem}"]`);
                    button.prop('disabled', true).text('Processing...');

                    fetch("{{ route('tasks.redeem') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams({ user_id: selectedUserIdForRedeem })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update the UI dynamically
                                button.prop('disabled', true).text('Redeemed');
                                button.replaceWith('<span class="badge bg-success">Redeemed</span>');

                                // Close the modal on success
                                const redeemModal = bootstrap.Modal.getInstance(document.getElementById('redeemConfirmationModal'));

                                if (redeemModal) {
                                    redeemModal.hide();
                                }

                                toastr.success('Redemption successful!');
                            } else {
                                // Show an error toast notification
                                toastr.error(data.message);
                                button.prop('disabled', false).text('Redeem');
                            }
                        })
                        .catch(error => {
                            console.error('Redeem error:', error);
                            toastr.error('Something went wrong while redeeming.');
                            button.prop('disabled', false).text('Redeem');
                        });
                }
            });
        });
    </script>

    <!-- Toast Container -->
    <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    </div>
@endsection
