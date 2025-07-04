@extends('layouts.admin')

@section('content')
    <style>
        #customer-table tbody tr {
            cursor: pointer;
            position: relative;
            z-index: 1;
        }

        .sticky-action {
            position: sticky;
            right: 0;
            background: #f8f8f8;
            z-index: 999;
            box-shadow: -2px 0 5px -2px rgba(0, 0, 0, 0.12);
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

        .table-card {
            min-height: 50vh;
            max-height: 90vh;
        }

        th {
            position: sticky !important;
            top: 0;
            background-color: #f8f9fa;
            z-index: 998;
        }

        .loader-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 50vh;
        }

        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="mt-4 row">
        <div class="mb-4 col-lg-12 mb-lg-0">
            <div class="card table-card py-3">
                {{-- <div class="p-3 pb-0 card-header">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">Customer</h6>
                    </div>
                </div> --}}
                <div class="p-3 px-4">
                    <div class="loader-container">
                        <div class="loader"></div>
                        <p class="mt-2">Loading...</p>
                    </div>
                   <table id="customer-table" class="display nowrap border" style="display: none; width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer Name</th>
                                <th>Guardian</th>
                                <th>Date of Birth</th>
                                <th>Number</th>
                                <th>Email</th>
                                <th>Booked Date</th>
                                <th>Booked Time</th>
                                <th>Sessions Chose</th>
                                <th>Attendee</th>
                                <th>Attended Status</th>
                                <th>Attended Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($appointments as $appointment)
                             <tr>
                                 <td>{{ $loop->iteration }}</td>
                                <td>{{ $appointment->user->fname ?? '' }} {{ $appointment->user->lname ?? '' }}</td>
                                <td>{{ $appointment->guardian ?? '' }}</td>
                                <td>{{ $appointment->user->dob ?? '' }}</td>
                                <td>{{ $appointment->user->number ?? '' }}</td>
                                <td>{{ $appointment->user->email ?? '' }}</td>
                                <td>{{ $appointment->appointmentDate->date ?? '' }}</td>
                                <td>{{ $appointment->workshop->time ?? '' }}</td>
                                <td>{{ $appointment->workshop->title ?? '' }}</td>
                                <td>{{ $appointment->attendee ?? '' }}</td>
                                <td>{{ $appointment->status ?? '' }}</td>
                                <td>Attended Time Scanned At</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-12 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Booking Summary</h6>
                </div>
                <div class="card-body p-3">
                    @if (!empty($summary))
                        @foreach ($summary as $day => $workshops)
                            <h6 class="text-uppercase">{{ $day }}</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Workshop</th>
                                            <th>Time</th>
                                            <th>Total Slot</th>
                                            <th>Booked Slot</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($workshops as $workshop)
                                            <tr>
                                                <td>{{ $workshop['workshop_name'] }}</td>
                                                <td>{{ $workshop['time'] }}</td>
                                                <td>{{ $workshop['total_slots'] }}</td>
                                                <td>{{ $workshop['booked_slots'] }}</td>
                                                <td>
                                                    @if ($workshop['balance'] == 0)
                                                        <span class="badge bg-danger">FULL</span>
                                                    @else
                                                        {{ $workshop['balance'] }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @else
                        <p>No booking summary available.</p>
                    @endif
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

    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">

    <!-- Include DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

    <!-- Include DataTables Buttons JS -->

    <script>
         // Show the loader
            $('.loader-container').show();
            $('#customer-table').hide();

            var permissionName = "";
            var table = $('#customer-table').DataTable({
                responsive: true,
                dom: "<'row'<'col-sm-12 col-md-2'l><'col-sm-12 col-md-8 text-center'B><'col-sm-12 col-md-2'f>>" +
                    "<'row'<'col-sm-12 table-responsive custom-table'tr>>" +
                    "<'d-flex justify-content-between'ip>",
                buttons: [{
                    extend: 'copy',
                    text: '<i class="fa fa-copy"></i> Copy',
                    className: 'btn btn-secondary'
                }, {
                    extend: 'csv',
                    text: '<i class="fa fa-file-csv"></i> CSV',
                    className: 'btn btn-info'
                }, {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel"></i> Excel',
                    className: 'btn btn-success'
                }, {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger'
                }, {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Print',
                    className: 'btn btn-primary'
                }],
                order: [
                    [0, 'desc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                } // Disable sorting on last column (Action)
                ],
                initComplete: function(settings, json) {
                    $('.loader-container').hide();
                    $('#customer-table').show();
                }
            });


            // Move the search input to the right side
            $('.dataTables_filter').addClass('float-end');
            $('.dataTables_filter label').addClass('w-100');

            // $('#customer-table tbody').on('click', 'tr', function(e) {
            //     // Prevent redirect if the clicked target is inside a delete button
            //     console.log('cliked');
            //     if ($(e.target).closest('.delete-user-btn').length) {
            //         return;
            //     }

            //     var userId = $(this).data('user-id');

            //     window.location.href = "{{ route('userData', ['user' => ':userId']) }}".replace(
            //         ':userId', userId);
            // });

            // Use event delegation for delete button
            $('#customer-table tbody').on('click', '.delete-user-btn', function(e) {
                e.stopPropagation(); // Prevent row click
                const appointmentId = $(this).data('appointment-id');
                const userName = $(this).data('user-name');

                let deleteUrl = @json(route('booking.destroy', ['id' => ':id']));
                deleteUrl = deleteUrl.replace(':id', appointmentId);

                $('#deleteUserForm').attr('action', deleteUrl);
                $('#deleteUserName').text(userName);
                $('#deleteUserModal').modal('show');
            });
    </script>

    @if(session('success'))
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
            <div id="successToast" class="toast align-items-center bg-success text-white border-0 fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="fa fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var toastEl = document.getElementById('successToast');
                if (toastEl) {
                    var toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                    toast.show();
                }
            });
        </script>
    @endif
@endsection
