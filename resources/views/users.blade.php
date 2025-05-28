@extends('layouts.admin')

@section('content')
    <style>
        #customer-table tbody tr {
            cursor: pointer;
        }
    </style>
    <div class="mt-4 row">
        <div class="mb-4 col-lg-12 mb-lg-0">
            <div class="card p-3">
                <div class="p-3 pb-0 card-header">
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
                <div id="table-container" class="table-responsive" style="display:none;">
                    <table id="customer-table" class="display nowrap" style="width:100%">
                     <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Number</th>
                            <th>Country</th>
                            <th>UTM Source</th>
                            <th>Created At</th>
                            <th>Appointments</th> {{-- Add this --}}
                            @foreach ($data['stations'] as $station)
                                <th>{{ $station['name']['name'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['users'] as $user)
                            <tr data-user-id="{{ $user->id }}">
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->fname }} {{ $user->lname }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->number }}</td>
                                <td>{{ $user->country }}</td>
                                <td>{{ $user->utm_source }}</td>
                                <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d M h:i A') : 'N/A' }}
                                </td>
                                <td>
                                    @forelse ($user->userAppointments as $ua)
                                        <div> {{ \Carbon\Carbon::createFromFormat('m-d-Y', $ua->appointment->name)->format('d M') }}</div>
                                    @empty
                                        <div>No dates are selected here</div>
                                    @endforelse
                                </td>
                                @foreach ($user['stations'] as $station)
                                    <td class="text-sm mb-0 {{ $station['value'] ? 'text-success' : 'text-danger' }}">
                                        {{ $station['value'] ? 'Yes' : 'No' }}
                                        @if($station['value'] && ($station['id'] == 6 || $station['id'] == 7))
                                            <br>
                                            <strong>Created At:</strong>
                                        {{ $station['created_at'] ? \Carbon\Carbon::parse($station['created_at'])->format('M d') : 'N/A'}}
                                        @endif
                                    </td>
                                @endforeach
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
        // $(document).ready(function() {
        //     $('#customer-table').DataTable({
        //         dom: 'Bfrtip',
        //         buttons: [
        //             'copy', 'excel', 'pdf', 'csv'
        //         ]
        //     });
        // });
        var permissionName = "{{ $permission }}";
        var table = $('#customer-table').DataTable({
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-6 col-md-6 align-items-end'B><'col-sm-12 col-md-3'f>>" +
                "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: ['copy','csv','excel','pdf','print'],
            order: [[0,'desc']],
            initComplete: function () {
                // Hide loader and show table
                $('#table-loader').hide();
                $('#table-container').show();
            }
        });


        // Move the search input to the left side
        $('.dataTables_filter').addClass('float-start');
        $('.dataTables_filter label').addClass('w-100');

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
