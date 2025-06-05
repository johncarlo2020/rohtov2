@extends('layouts.admin')

@section('content')
    <style>
        #customer-table tbody tr {
            cursor: pointer;
        }
        .bottle-image {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }
    </style>
    <div class="mt-4 row">
        <div class="mb-4 col-lg-12 mb-lg-0">
            <div class="card px-3">
                <div class="p-3 pb-0 card-header">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">Customer</h6>
                    </div>
                </div>
                <div class="table-responsive">
                        <table id="vote-table" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                 <th class="no-export">Bottle Image</th>
                                <th>Bottle ID</th>

                                <th>Votes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['userVote'] as $bottleId => $voteData)
                                <tr>
                                    <td><img class="bottle-image" src="{{ asset('files/vote/' . $bottleId . '.webp') }}" alt="Option {{ $bottleId }}"></td>
                                    <td>{{ $bottleId }}</td>

                                    <td>{{ $voteData['count'] }}</td>
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
        var table = $('#vote-table').DataTable({
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-6 col-md-6'B><'col-sm-12 col-md-3'f>>" +
                "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'csv',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'excel',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'pdf',
                    exportOptions: { columns: ':not(.no-export)' }
                },
                {
                    extend: 'print',
                    exportOptions: { columns: ':not(.no-export)' }
                }
            ],
            order: [
                [0, 'desc']
            ]
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
