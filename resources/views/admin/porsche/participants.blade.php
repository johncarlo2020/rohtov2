@extends('layouts.admin')

@section('content')
<div class="row pt-2 mt-4">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Participants</p>
                            <h5 class="font-weight-bolder" id="metricTotalParticipants">-</h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                            <i class="fa-solid fa-user text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Active Filtered</p>
                            <h5 class="font-weight-bolder" id="metricFiltered">-</h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                            <i class="fa-solid fa-filter text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 row">
    <div class="mb-4 col-lg-12 mb-lg-0">
        <div class="card table-card py-3">
            <div class="p-3 pb-0 card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="mb-0">Participants</h6>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <input type="date" id="filterDateFrom" class="form-control form-control-sm w-auto" placeholder="From">
                        <span class="text-xs text-muted">to</span>
                        <input type="date" id="filterDateTo" class="form-control form-control-sm w-auto" placeholder="To">
                        <button type="button" id="btnFilter" class="btn btn-sm btn-dark mb-0 py-1 px-3">Filter</button>
                        <button type="button" id="btnReset" class="btn btn-sm btn-outline-secondary mb-0 py-1 px-2">Reset</button>
                        <button type="button" id="btnExport" class="btn btn-primary btn-sm mb-0 py-1 px-3">
                            <i class="fa fa-download me-1"></i> Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive p-3 px-md-4">
                <table id="customer-table" class="display nowrap border" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th class="text-center">Consent</th>
                            <th class="text-center">Q1</th>
                            <th class="text-center">Q2</th>
                            <th class="text-center">Q3</th>
                            <th class="text-center">Q4</th>
                            <th class="text-center">Result</th>
                            <th class="text-center">Total Entries</th>
                            <th class="text-center">Registered At</th>
                            <th class="text-center">Last Activity</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script>
$(document).ready(function () {
    const table = $('#customer-table').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ordering: true,
        scrollX: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ajax: {
            url: "{{ route('admin.participants') }}",
            data: function (d) {
                d.date_from = $('#filterDateFrom').val();
                d.date_to = $('#filterDateTo').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'full_name', name: 'full_name' },
            { data: 'contact_number', name: 'contact_number' },
            { data: 'email', name: 'email' },
            { data: 'consent', name: 'consent_given', className: 'text-center' },
            { data: 'q1', name: 'q1', className: 'text-center', orderable: false, searchable: false },
            { data: 'q2', name: 'q2', className: 'text-center', orderable: false, searchable: false },
            { data: 'q3', name: 'q3', className: 'text-center', orderable: false, searchable: false },
            { data: 'q4', name: 'q4', className: 'text-center', orderable: false, searchable: false },
            { data: 'result', name: 'result', className: 'text-center', orderable: false, searchable: false },
            { data: 'total_entries', name: 'total_entries', className: 'text-center', orderable: false },
            { data: 'registered_at', name: 'registered_at', className: 'text-center' },
            { data: 'last_activity', name: 'last_activity', className: 'text-center', orderable: false },
            { data: 'actions', name: 'actions', className: 'text-center', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        drawCallback: function (settings) {
            const json = settings.json;
            if (json) {
                if (typeof json.recordsTotal !== 'undefined') {
                    $('#metricTotalParticipants').text(Number(json.recordsTotal).toLocaleString());
                }
                if (typeof json.recordsFiltered !== 'undefined') {
                    $('#metricFiltered').text(Number(json.recordsFiltered).toLocaleString());
                }
            }
        }
    });

    $('#btnFilter').on('click', function () {
        table.ajax.reload();
    });

    $('#btnReset').on('click', function () {
        $('#filterDateFrom').val('');
        $('#filterDateTo').val('');
        table.ajax.reload();
    });

    $('#btnExport').on('click', function (e) {
        e.preventDefault();
        const from = $('#filterDateFrom').val();
        const to = $('#filterDateTo').val();
        let url = "{{ route('admin.reports.export.participants') }}";
        const params = new URLSearchParams();
        if (from) params.append('date_from', from);
        if (to) params.append('date_to', to);
        if (params.toString()) {
            url += '?' + params.toString();
        }
        window.location.href = url;
    });
});
</script>
@endsection
