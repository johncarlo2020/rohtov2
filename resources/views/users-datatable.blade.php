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
                        <h6 class="mb-2">Participants</h6>
                        <small>Stations: grey = pending · gold ✓ = completed</small>
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
                                <th>Email</th>
                                <th>Number</th>
                                <th>Country</th>
                                <th>Email consent</th>
                                <th>Card applied</th>
                                <th>Terms accepted</th>
                                <th>Marketing consent</th>
                                <th>Age 21+ confirmed</th>

                                <th>Created At</th>
                                <th>Stations</th>
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
        var isAdmin = {{ ($isAdmin ?? false) ? 'true' : 'false' }};
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
                { data: 'email', name: 'email' },
                { data: 'number', name: 'number' },
                { data: 'country', name: 'country' },
                { data: 'email_consent', name: 'email_consent' },
                { data: 'isCardApply', name: 'isCardApply' },
                { data: 'terms', name: 'terms' },
                { data: 'marketing', name: 'marketing' },
                { data: 'age_confirmed', name: 'age_confirmed' },
                { data: 'created_at', name: 'created_at' },
                {
                    data: 'stations', orderable: false, searchable: false,
                    render: function(stations, type) {
                        if (type !== 'display') return stations.filter(station => station.completed).length;
                        const group = document.createElement('div');
                        group.className = 'participant-stations';
                        stations.forEach(station => {
                            const circle = document.createElement('span');
                            circle.className = 'participant-station ' + (station.completed ? 'is-completed' : 'is-pending');
                            circle.textContent = station.id;
                            circle.title = station.name + ': ' + (station.completed ? 'Completed' : 'Pending');
                            circle.setAttribute('aria-label', circle.title);
                            if (station.completed) {
                                const check = document.createElement('span');
                                check.className = 'participant-station-check';
                                check.textContent = '✓';
                                check.setAttribute('aria-hidden', 'true');
                                circle.appendChild(check);
                            }
                            group.appendChild(circle);
                        });
                        return group.outerHTML;
                    }
                },
            ],
            dom: "<'participant-table-toolbar'lfB>" +
                 "<'table-responsive custom-table'tr>" +
                 "<'participant-table-footer'ip>",
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            pagingType: 'simple_numbers',
            language: {
                lengthMenu: 'Show _MENU_',
                info: '_START_–_END_ of _TOTAL_ participants',
                infoEmpty: 'No participants',
                paginate: { previous: 'Previous', next: 'Next' },
                emptyTable: 'No participants yet',
                zeroRecords: 'No matching participants'
            },
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
