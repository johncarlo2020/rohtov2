@extends('layouts.admin')

@section('content')
    <style>
        #customer-table tbody tr {
            cursor: pointer;
        }
        .task-image {
            max-width: 50px; /* Adjust as needed */
            max-height: 50px; /* Adjust as needed */
            margin: 2px;
        }
    </style>
    <div class="mt-4 row">
        <div class="mb-4 col-lg-12 mb-lg-0">
            <div class="card">
                <div class="p-3 pb-0 card-header">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">Customer Tasks</h6>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="customer-table" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                @if($users->isNotEmpty() && $users->first()->all_tasks->isNotEmpty())
                                @foreach ($users->first()->all_tasks as $task)
                                <th>{{ $task->name }} (Status/Image)</th>
                                @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr data-user-id="{{ $user->id }}">
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->fname }} {{ $user->lname }}</td>
                                <td>{{ $user->number ?? 'none' }}</td>
                                <td>{{ $user->email }}</td>

                            <!-- Inside your table row generation loop -->
                                @foreach ($user->all_tasks as $task)
                                <td>
                                    @php
                                    $imageColumn = 'task_' . $task->id . '_image';
                                    @endphp
                                    @if (ucfirst($task->status) == 'In-progress' && !empty($user->$imageColumn))
                                    <img src="{{ asset('storage/uploads/' . $user->$imageColumn) }}" alt="Task Image" class="clickable-image"
                                        data-task-id="{{ $task->id }}" data-user-id="{{ $user->id }}"
                                        data-image="{{ asset('storage/uploads/' . $user->$imageColumn) }}"
                                        style="max-width: 60px; max-height: 60px; cursor: pointer;">
                                    @else
                                    {{ ucfirst($task->status) }}
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
        var table = $('#customer-table').DataTable({
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-6 col-md-6 align-items-end'B><'col-sm-12 col-md-3'f>>" +
                "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            order: [
                [0, 'desc']
            ]
        });


        // Move the search input to the left side
        $('.dataTables_filter').addClass('float-start');
        $('.dataTables_filter label').addClass('w-100');


        $(document).ready(function () {
                let selectedTaskId = null;
                let selectedUserId = null;

                $('.clickable-image').on('click', function () {
                    selectedTaskId = $(this).data('task-id');
                    selectedUserId = $(this).data('user-id');
                    const imageUrl = $(this).data('image');

                    $('#modal-task-image').attr('src', imageUrl);
                    $('#taskImageModal').modal('show');
                });

                $('#confirm-completion').on('click', function () {
                    $.ajax({
                        url: '/tasks/complete',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            user_id: selectedUserId,
                            task_id: selectedTaskId
                        },
                        success: function (response) {
                            $('#taskImageModal').modal('hide');
                            location.reload(); // or just update the row with JS
                        },
                        error: function () {
                            alert('Failed to update status.');
                        }
                    });
                });
            });


    </script>
@endsection
