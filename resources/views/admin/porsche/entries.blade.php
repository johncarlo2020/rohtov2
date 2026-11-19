@extends('layouts.admin')

@section('content')
<div class="row pt-2 mt-4">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Entries</p>
                            <h5 class="font-weight-bolder" id="metricTotalEntries">-</h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                            <i class="fa-solid fa-flag-checkered text-lg opacity-10" aria-hidden="true"></i>
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
                        <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
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
                    <h6 class="mb-0">Quiz Entries</h6>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <select id="filterResultType" class="form-select form-select-sm w-auto">
                            <option value="">All Results (A, B, C)</option>
                            <option value="A">Result A</option>
                            <option value="B">Result B</option>
                            <option value="C">Result C</option>
                        </select>
                        <input type="date" id="filterDateFrom" class="form-control form-control-sm w-auto" placeholder="From">
                        <span class="text-xs text-muted">to</span>
                        <input type="date" id="filterDateTo" class="form-control form-control-sm w-auto" placeholder="To">
                        <button type="button" id="btnFilter" class="btn btn-sm btn-dark mb-0 py-1 px-3">Filter</button>
                        <button type="button" id="btnReset" class="btn btn-sm btn-outline-secondary mb-0 py-1 px-2">Reset</button>
                        <button type="button" class="btn btn-primary btn-sm mb-0 py-1 px-3" data-bs-toggle="modal" data-bs-target="#exportModal">
                            <i class="fa fa-download me-1"></i> Export
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-3 px-4">
                <table id="customer-table" class="display nowrap border" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Entry ID</th>
                            <th>Participant</th>
                            <th class="text-center">Attempt</th>
                            <th class="text-center">Answers</th>
                            <th class="text-center">Result</th>
                            <th class="text-center">Completed At</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Export Modal matching users.blade.php style --}}
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Quiz Entries</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label text-sm fw-bold">Result Type</label>
                    <select id="exportResultType" class="form-select">
                        <option value="">All Results</option>
                        <option value="A">Result A</option>
                        <option value="B">Result B</option>
                        <option value="C">Result C</option>
                    </select>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label text-sm fw-bold">Date From</label>
                        <input type="date" id="exportStartDate" class="form-control">
                    </div>
                    <div class="col-6">
                        <label class="form-label text-sm fw-bold">Date To</label>
                        <input type="date" id="exportEndDate" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button id="confirmExport" class="btn btn-primary">Export CSV</button>
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
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ajax: {
            url: "{{ route('admin.entries') }}",
            data: function (d) {
                d.result_type = $('#filterResultType').val();
                d.date_from = $('#filterDateFrom').val();
                d.date_to = $('#filterDateTo').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'participant', name: 'porsche_registration_id' },
            { data: 'attempt_number', name: 'attempt_number', className: 'text-center' },
            { data: 'answers', name: 'question_1_answer', className: 'text-center' },
            { data: 'result', name: 'result_type', className: 'text-center' },
            { data: 'completed_at', name: 'completed_at', className: 'text-center' }
        ],
        order: [[0, 'desc']],
        drawCallback: function (settings) {
            const json = settings.json;
            if (json) {
                if (typeof json.recordsTotal !== 'undefined') {
                    $('#metricTotalEntries').text(Number(json.recordsTotal).toLocaleString());
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

    $('#filterResultType').on('change', function () {
        table.ajax.reload();
    });

    $('#btnReset').on('click', function () {
        $('#filterResultType').val('');
        $('#filterDateFrom').val('');
        $('#filterDateTo').val('');
        table.ajax.reload();
    });

    $('#confirmExport').on('click', function () {
        const resultType = $('#exportResultType').val();
        const from = $('#exportStartDate').val();
        const to = $('#exportEndDate').val();
        let url = "{{ route('admin.reports.export.entries') }}";
        const params = new URLSearchParams();
        if (resultType) params.append('result_type', resultType);
        if (from) params.append('date_from', from);
        if (to) params.append('date_to', to);
        if (params.toString()) {
            url += '?' + params.toString();
        }
        window.location.href = url;
        bootstrap.Modal.getInstance(document.getElementById('exportModal')).hide();
    });
});
</script>
@endsection
