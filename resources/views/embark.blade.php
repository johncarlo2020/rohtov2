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
    </style>

    <div class="mt-4 row">
        <div class="mb-4 col-lg-12 mb-lg-0">
            <div class="card p-3">
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
                                        <th>{{ $task->name }} (Date)</th>
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

                                    @foreach ($user->all_tasks as $task)
                                        <td>
                                            @if (ucfirst($task->status) === 'In-progress' && !empty($task->image))
                                                <img src="{{ asset('storage/uploads/' . $task->image) }}" alt="Task Image" class="clickable-image"
                                                    data-task-id="{{ $task->id }}" data-user-id="{{ $user->id }}"
                                                    data-image="{{ asset('storage/uploads/' . $task->image) }}"
                                                    style="max-width: 60px; max-height: 60px; cursor: pointer;">
                                            @else
                                                {{ ucfirst($task->status) }}
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $task->submission_date ? \Carbon\Carbon::parse($task->submission_date)->format('d M h:i A') : 'N/A' }}</small> {{-- Formatted date and time with AM/PM --}}
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

    <script>
        const table = $('#customer-table').DataTable({
            responsive: true,
            dom: "<'row'<'col-sm-12 col-md-3'l><'col-sm-6 col-md-6 align-items-end'B><'col-sm-12 col-md-3'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
            order: [[0, 'desc']]
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
        });
    </script>
@endsection
