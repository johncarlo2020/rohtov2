@extends('layouts.admin')

@section('content')
    <style>
        #customer-table tbody tr {
            cursor: pointer;
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

        .table-card {
            min-height: 50vh;
            max-height: 90vh;
        }
        .filter {
             position: absolute;
            top: 60px;
            right: 21px;
            z-index: 100;
        }
    </style>
    <div class="mt-4 row">
        <div class="mb-4 col-lg-12 mb-lg-0">
            <div class="card p-3">

                <div class="p-2 pb-0 card-header">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">Customer</h6>
                    </div>
                </div>
                <!-- Loader shown while DataTable initializes -->
                <div id="table-loader" class="text-center my-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <!-- Container hidden until DataTable init completes -->
                <div id="table-container" class="px-1" style="display:none;">
                    <table id="customer-table" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Date of birth</th>
                                <th>Email</th>
                                <th>Number</th>
                                <th>Country</th>
                                <th>UTM Source</th>
                                <th>SMS</th>
                                <th>Email consent</th>
                                <th>Alliance Bank</th>

                                <th>Created At</th>
                                <th>Appointments</th> {{-- Add this --}}
                                @foreach ($data['stations'] as $station)
                                    <th>{{ $station['name'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">

    <!-- Include DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
    <!-- Bootstrap Bundle JS (includes modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

    <script>
        var permissionName = "{{ $permission }}";
        var table = $('#customer-table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('users.datatable') }}",
            columns: [
                {
                    data: 'id',
                    name: 'id',
                    render: function(data, type, row) {
                        return data; // Allow HTML rendering
                    }
                },
                { data: 'name', name: 'name' },
                { data: 'dob', name: 'dob' },
                { data: 'email', name: 'email' },
                { data: 'number', name: 'number' },
                { data: 'country', name: 'country' },
                { data: 'utm_source', name: 'utm_source' },
                { data: 'sms_consent', name: 'sms_consent' },
                { data: 'email_consent', name: 'email_consent' },
                { data: 'alliance_bank', name: 'alliance_bank' },
                { data: 'created_at', name: 'created_at' },
                { data: 'appointment_dates_string', name: 'appointment_dates_string', orderable: false, searchable: false },
                    @foreach ($data['stations'] as $station)
                        {
                            data: 'stations.{{ $loop->index }}.display_value',
                            name: 'stations.{{ $loop->index }}.display_value',
                            orderable: false,
                            searchable: false
                        },
                    @endforeach
            ],
            dom: "<'row'<'col-sm-12 col-md-6'><'col-sm-12 col-md-6 d-flex justify-content-end'fB>>" +
                 "<'row'<'col-sm-12 table-responsive my-2 custom-table'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'li><'col-sm-12 col-md-7'p>>",
            searching: true,
            buttons: [
                {
                    text: '<i class="fa-solid fa-file-csv"></i> Export to CSV',
                    className: 'btn btn-outline-secondary ms-2',
                    action: function ( e, dt, node, config ) {
                        window.location.href = "{{ route('users.export') }}";
                    }
                }
            ],
            order: [
                [0, 'desc']
            ],
            initComplete: function() {
                // Hide loader and display table container when initialized
                $('#table-loader').hide();
                $('#table-container').show();
            }
        });


        $('#customer-table tbody').on('click', 'tr', function() {

            // Get data from the clicked row
            var data = table.row(this).data();

            if (data) {
                // Extract user ID from the clicked row's data
                var userId = data.id;

                // Extract just the numeric ID from the HTML (remove badges and HTML)
                var numericId = userId.replace(/&nbsp;/g, ' ').replace(/<[^>]*>/g, '').trim().split(' ')[0];

                // Redirect to the user data route with the user ID
                if (numericId) {
                    window.location.href = "{{ route('userData', ['user' => ':userId']) }}".replace(':userId', numericId);
                }
            }
        });
    </script>


@endsection
