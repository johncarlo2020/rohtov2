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
            background: #f0f0f0;
            z-index: 21;
            box-shadow: -2px 0 5px -2px rgba(0, 0, 0, 0.12);
        }

    </style>
    <div class="mt-4 row">
        <div class="mb-4 col-lg-12 mb-lg-0">
            <div class="card py-3">
                <div class="p-3 pb-0 card-header">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">Customer</h6>
                    </div>
                </div>
                <div class="table-responsive mx-3">
                    <table id="customer-table" class="display nowrap" style="min-width:1800px;width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>DOB</th>
                                <th>Age</th>
                                <th>Number</th>
                                <th>Country</th>
                                <th>Existing</th>
                                <th>Social Media</th>
                                <th>Appeal</th>
                                @foreach ($data['stations'] as $station)
                                    <th>{{ $station['name'] }}</th>
                                @endforeach
                                <th class="sticky-action">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['users'] as $user)
                                <tr data-user-id="{{ $user->id }}">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->fname }} {{ $user->lname }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->dob }}</td>

                                    <td>{{ $user->age }}</td>

                                    <td>{{ $user->number }}</td>
                                    <td>{{ $user->country }}</td>
                                    <td>{{ $user->existing }}</td>
                                    <td>
                                        @php
                                            $platforms = json_decode($user->social_media, true);
                                        @endphp

                                        {{ !empty($platforms) ? implode(', ', $platforms) : 'Not Following' }}
                                    </td>

                                    <td>{{ $user->appeal }}</td>


                                    @foreach ($user['stations'] as $station)
                                        <td class="text-sm mb-0 {{ $station['value'] ? 'text-success' : 'text-danger' }}">
                                            {{ $station['value'] ? 'Yes' : 'No' }}</td>
                                    @endforeach

                                    <td class="button-delete sticky-action">
                                        <button class="btn btn-danger btn-sm delete-user-btn"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->fname }} {{ $user->lname }}">Delete</button>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            responsive: false,
            dom: "<'row'<'col-sm-12 col-md-2'l><'col-sm-6 col-md-6 align-items-end'B><'col-sm-12 col-md-3'f>>" +
                "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-2'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fa fa-copy"></i> Copy',
                    className: 'btn btn-secondary'
                },
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-csv"></i> CSV',
                    className: 'btn btn-info'
                },
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel"></i> Excel',
                    className: 'btn btn-success'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger'
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Print',
                    className: 'btn btn-primary'
                }
            ],
            order: [
                [0, 'desc']
            ],
            columnDefs: [
                { orderable: false, targets: -1 } // Disable sorting on last column (Action)
            ]
        });


        // Move the search input to the right side
        $('.dataTables_filter').addClass('float-end');
        $('.dataTables_filter label').addClass('w-100');

        $('#customer-table tbody').on('click', 'tr', function(e) {
            // Prevent redirect if the clicked target is inside a delete button
            console.log('cliked');
            if ($(e.target).closest('.delete-user-btn').length) {
                return;
            }

            var userId = $(this).data('user-id');

            window.location.href = "{{ route('userData', ['user' => ':userId']) }}".replace(':userId', userId);
        });

        // Use event delegation for delete button
        $('#customer-table tbody').on('click', '.delete-user-btn', function(e) {
            e.stopPropagation(); // Prevent row click
            const userId = $(this).data('user-id');
            const userName = $(this).data('user-name');

            let deleteUrl = @json(route('users.destroy', ['id' => ':id']));
            deleteUrl = deleteUrl.replace(':id', userId);

            $('#deleteUserForm').attr('action', deleteUrl);
            $('#deleteUserName').text(userName);
            $('#deleteUserModal').modal('show');
        });
    </script>
@endsection
