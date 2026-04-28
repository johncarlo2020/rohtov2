@extends('layouts.admin')

@section('content')
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
            <div id="alert-box"></div>
            <table id="gifts-table" class="display nowrap border" style="display: none; width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gift Name</th>
                            <th>Stock Level</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($gifts as $gift)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $gift->name }}</td>

                            <td>
                                <span class="
                                    {{ $gift->stock_level == 0 ? 'text-danger' : 
                                    ($gift->stock_level < 5 ? 'text-warning' : 'text-success') }}">
                                    {{ $gift->stock_level }}
                                </span>
                            </td>

                            <td>
                                @if ($gift->stock_level == 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif ($gift->stock_level < 5)
                                    <span class="badge bg-warning text-dark">Low Stock</span>
                                @else
                                    <span class="badge bg-success">Available</span>
                                @endif
                            </td>

                            <td>
                                <button class="btn btn-success btn-sm add-stock-btn"
                                    data-id="{{ $gift->id }}">
                                    + Add
                                </button>

                                <button class="btn btn-danger btn-sm deduct-stock-btn"
                                    data-id="{{ $gift->id }}">
                                    - Deduct
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- ADD STOCK MODAL -->
<div class="modal fade" id="addStockModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modalTitle">Add Stock</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="number" id="stockAmount" class="form-control" placeholder="Enter amount">
                <input type="hidden" id="giftId">
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="confirmAddStock">Add</button>
            </div>
        </div>
    </div>
</div>

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
    $('#gifts-table').hide();

    let exportType = 'export';

    const today = new Date().toISOString().split('T')[0];

    $('#startDate').val(today);
    $('#endDate').val(today);

    $('#startDate, #endDate').on('change', function () {
        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();

        if (startDate && endDate && endDate < startDate) {
            alert('"To date" cannot be earlier than "From date".');
            $('#endDate').val(startDate); // auto-fix
        }
    });

    $('#startDate').on('change', function () {
        const startDate = $(this).val();
        $('#endDate').attr('min', startDate);
    });

    /* ===================== DATATABLE INIT ===================== */
    var table = $('#gifts-table').DataTable({
        responsive: true,
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6 d-flex justify-content-end'f>>" +
            "<'row'<'col-sm-12 table-responsive custom-table' tr>>" +
            "<'row'<'d-flex justify-content-start col-sm-12 col-md-6 mt-3'i>" +
            "<'col-sm-12 col-md-6 mt-3 d-flex justify-content-end'p>>",

        buttons: [
            {
                extend: 'csvHtml5',
                className: 'd-none btn-export-csv',
                filename: function () {
                    return exportFileName; // ✅ ALWAYS correct
                },
                exportOptions: {
                    columns: ':not(:last-child)',
                    modifier: { search: 'applied' }
                }
            },
            {
                extend: 'excelHtml5',
                className: 'd-none btn-export-excel',
                filename: function () {
                    return exportFileName; // ✅ ALWAYS correct
                },
                exportOptions: {
                    columns: ':not(:last-child)',
                    modifier: { search: 'applied' }
                }
            },
            {
                text: '<i class="fa fa-file-csv"></i> CSV',
                className: 'btn btn-info',
                action: function () {
                    exportType = 'csv';
                    $('#exportModal').modal('show');
                }
            },
            {
                text: '<i class="fa fa-file-excel"></i> Excel',
                className: 'btn btn-success',
                action: function () {
                    exportType = 'excel';
                    $('#exportModal').modal('show');
                }
            }
        ],

        order: [[0, 'desc']],

        columnDefs: [
            {
                orderable: false,
                targets: -1 // Action/Delete column
            }
        ],

        initComplete: function () {
            $('.loader-container').hide();
            $('#gifts-table').show();

            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(
                document.querySelectorAll('[data-bs-toggle="tooltip"]')
            );
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
        }
    });

    /* ===================== DATE RANGE FILTER ===================== */
    $.fn.dataTable.ext.search.push(function (settings, data) {
    let start = $('#startDate').val();
    let end = $('#endDate').val();

    // Timestamp column (second last column)
    let rowDate = new Date(data[data.length - 2]);

    // Convert row date to YYYY-MM-DD (DATE ONLY)
    let rowDateOnly = rowDate.getFullYear() + '-' +
        String(rowDate.getMonth() + 1).padStart(2, '0') + '-' +
        String(rowDate.getDate()).padStart(2, '0');

    if (!start && !end) return true;

    if (start && rowDateOnly < start) return false;
    if (end && rowDateOnly > end) return false;

    return true;
});

    /* ===================== DATE FILTER BUTTONS ===================== */
    $('#filterDate').on('click', function () {
        table.draw();
    });

    $('#resetDate').on('click', function () {
        $('#startDate').val('');
        $('#endDate').val('');
        table.draw();
    });

    /* ===================== EXPORT CONFIRM ===================== */
    $('#confirmExport').on('click', function () {
    // ✅ Store filename BEFORE triggering export
    exportFileName = $('#exportFileName').val().trim();

    if (!exportFileName) {
        alert('Please enter a file name');
        return;
    }

    if (exportType === 'csv') {
        table.button('.btn-export-csv').trigger();
    }

    if (exportType === 'excel') {
        table.button('.btn-export-excel').trigger();
    }

    // ✅ Clear AFTER export
    $('#exportModal').modal('hide');
    $('#exportFileName').val('');
});

    /* ===================== SEARCH INPUT POSITION ===================== */
    $('.dataTables_filter').addClass('float-end');
    $('.dataTables_filter label').addClass('w-100');

    /* ===================== ROW CLICK REDIRECT ===================== */

    /* ===================== DELETE BUTTON ===================== */
    $('#gifts-table tbody').on('click', '.delete-user-btn', function (e) {
        e.stopPropagation();

        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');

        let deleteUrl = @json(route('users.destroy', ['id' => ':id']));
        deleteUrl = deleteUrl.replace(':id', userId);

        $('#deleteUserForm').attr('action', deleteUrl);
        $('#deleteUserName').text(userName);
        $('#deleteUserModal').modal('show');
    });
</script>

<script>
    let actionType = 'add';

    // ➕ ADD
    $(document).on('click', '.add-stock-btn', function () {
        actionType = 'add';

        let id = $(this).data('id');

        $('#giftId').val(id);
        $('#stockAmount').val('');

        // 🔥 Dynamic UI
        $('#modalTitle').text('Add Stock');
        $('#confirmAddStock')
            .text('Add')
            .removeClass('btn-danger')
            .addClass('btn-primary');

        $('#addStockModal').modal('show');
    });

    // ➖ DEDUCT
    $(document).on('click', '.deduct-stock-btn', function () {
        actionType = 'deduct';

        let id = $(this).data('id');

        $('#giftId').val(id);
        $('#stockAmount').val('');

        // 🔥 Dynamic UI
        $('#modalTitle').text('Deduct Stock');
        $('#confirmAddStock')
            .text('Deduct')
            .removeClass('btn-primary')
            .addClass('btn-danger');

        $('#addStockModal').modal('show');
    });
    

    $('#confirmAddStock').on('click', function () {
        let id = $('#giftId').val();
        let amount = $('#stockAmount').val();

        if (!amount || amount <= 0) {
            showAlert('Invalid amount', 'danger');
            return;
        }

        $.ajax({
            url: `{{ route('gifts.update', ':id') }}`.replace(':id', id),
            type: 'POST',
            data: {
                _method: 'PUT',
                stock_level: amount,
                action: actionType,
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                let name = res.data.name;
                let total = res.data.current_stock;

                let message = actionType === 'add'
                    ? `Added ${amount} to "${name}" (Total: ${total})`
                    : `Deducted ${amount} from "${name}" (Total: ${total})`;

                showAlert(message, 'success');

                $('#addStockModal').modal('hide');

                let row = $(`button[data-id="${id}"]`).closest('tr');

                // Update stock
                row.find('td:nth-child(3)').text(total);

                // Update status
                let statusHtml = '';
                if (total == 0) {
                    statusHtml = `<span class="badge bg-danger">Out of Stock</span>`;
                } else if (total < 5) {
                    statusHtml = `<span class="badge bg-warning text-dark">Low Stock</span>`;
                } else {
                    statusHtml = `<span class="badge bg-success">Available</span>`;
                }

                row.find('td:nth-child(4)').html(statusHtml);
            },
            error: function (xhr) {
                let message = xhr.responseJSON?.message || 'Error';
                showAlert(message, 'danger');
            }
        });
    });

    function showAlert(message, type = 'success') {
        let alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        $('#alert-box').html(alertHtml);

        // auto close after 3 seconds
        setTimeout(() => {
            $('.alert').alert('close');
        }, 3000);
    }
    </script>

@endsection