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
                       {{-- <div class="date-picker d-flex align-items-center gap-2 filter">
                            <input type="date" id="user-date" class="form-control flex-grow-0" style="width:200px;" value="{{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('Y-m-d') : '' }}" onchange="window.location.href='{{ route('userFilter', ['date' => ':date']) }}'.replace(':date', this.value)">

                            <div class="input-group flex-grow-0" style="width:260px;">
                                <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
                                <input class="form-control" type="text" placeholder="Search by keyword" aria-label="Search by keyword" id="keyword" value="{{ $keyword }}" onkeyup="window.location.href='{{ route('userFilter', ['date' => ':date', 'keyword' => ':keyword']) }}'.replace(':date', '{{ $selectedDate ? \Carbon\Carbon::parse($selectedDate)->format('Y-m-d') : '' }}').replace(':keyword', this.value)">
                            </div>
                        </div> --}}
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
                                <th>Redeem date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['users'] as $user)
                                <tr data-user-id="{{ $user['id'] }}">
                                    <td>{{ $user['id'] }}</td>
                                    <td>{{ $user['fname'] }} {{ $user['lname'] }}</td>
                                    <td>
                                        {{ $user['dob'] }}
                                    </td>
                                    <td>{{ $user['email'] }}</td>
                                    <td>{{ $user['number'] }}</td>
                                    <td>{{ $user['country'] }}</td>
                                    <td>{{ $user['utm_source'] }}</td>
                                    <td>{{ $user['sms_consent'] ? 'Yes' : 'No' }}</td>
                                    <td>{{ $user['email_consent'] ? 'Yes' : 'No' }}</td>
                                    <td>{{ $user['alliance_bank'] ? 'Yes' : 'No' }}</td>


                                    <td>{{ $user['created_at'] }}</td>
                                    <td>
                                        {{ $user['appointment_dates_string'] }}
                                    </td>
                                    @foreach ($user['stations'] as $station)
                                        <td class="text-sm mb-0 {{ $station['value'] ? 'text-success' : 'text-danger' }}">
                                            {{ $station['display_value'] }}
                                        </td>
                                    @endforeach
                                    <td>
                                        {{ $user['redeem_date'] }}
                                </tr>
                            @endforeach
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
            dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-6 text-center'B><'col-sm-12 col-md-3'f>>" +
                 "<'row'<'col-sm-12 table-responsive my-2 custom-table'tr>>" +
                 "<'d-flex justify-content-between'ip>",
            searching: true,
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="fa-solid fa-file-csv"></i> CSV (chunked)',
                    className: 'btn btn-outline-secondary',
                    buttons: function (dt) {
                        var buttons = [];
                        var rows = dt.rows().count();
                        var chunkSize = 1000;
                        var chunks = Math.ceil(rows / chunkSize);

                        for (let i = 0; i < chunks; i++) {
                            let start = i * chunkSize;
                            let end = start + chunkSize;
                            buttons.push({
                                extend: 'csvHtml5',
                                text: 'Chunk ' + (i + 1) + ' (' + start + ' - ' + (end - 1) + ')',
                                exportOptions: {
                                    rows: function ( idx ) {
                                        return idx >= start && idx < end;
                                    }
                                },
                                filename: 'Users-Chunk-' + (i + 1)
                            });
                        }
                        return buttons;
                    }
                },
                {
                    extend: 'collection',
                    text: '<i class="fa-solid fa-file-excel"></i> Excel (chunked)',
                    className: 'btn btn-outline-success',
                    buttons: function (dt) {
                        var buttons = [];
                        var rows = dt.rows().count();
                        var chunkSize = 1000;
                        var chunks = Math.ceil(rows / chunkSize);

                        for (let i = 0; i < chunks; i++) {
                            let start = i * chunkSize;
                            let end = start + chunkSize;
                            buttons.push({
                                extend: 'excelHtml5',
                                text: 'Chunk ' + (i + 1) + ' (' + start + ' - ' + (end - 1) + ')',
                                exportOptions: {
                                    rows: function ( idx ) {
                                        return idx >= start && idx < end;
                                    }
                                },
                                filename: 'Users-Chunk-' + (i + 1)
                            });
                        }
                        return buttons;
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

            // Extract user ID from the clicked row's data
            var userId = $(this).data('user-id');

            // Redirect to the user data route with the user ID
            window.location.href = "{{ route('userData', ['user' => ':userId']) }}".replace(':userId', userId);
        });
    </script>


@endsection
