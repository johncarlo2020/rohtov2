@extends('layouts.admin')

@section('content')
@php
    use Carbon\Carbon;
@endphp
<div class="row pt-2 mt-4">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Customers</p>
                            <h5 class="font-weight-bolder">
                                {{ $data['usersCount'] }}
                            </h5>
                            {{-- <p class="mb-0">
                                <span class="text-success text-sm font-weight-bolder">+55%</span>
                                since yesterday
                            </p> --}}
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
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Today's Customer</p>
                            <h5 class="font-weight-bolder">
                                {{ $data['userToday'] }}
                            </h5>
                            {{-- <p class="mb-0">
                                <span class="text-success text-sm font-weight-bolder">+3%</span>
                                since last week
                            </p> --}}
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                            <i class="fa-solid fa-calendar-day text-lg opacity-10" aria-hidden="true"></i>
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
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Completion Rate</p>
                            <h5 class="font-weight-bolder">
                                {{ $data['percentage'] }}%
                            </h5>

                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                            <i class="fa-solid fa-percent text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Customers Finished</p>
                            <h5 class="font-weight-bolder">
                                {{ $data['completedUsers'] }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                            <i class="fa-solid fa-circle-check text-lg opacity-10" aria-hidden="true"></i>
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
                <table id="customer-table" class="display nowrap border" style="display: none; width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fullname</th>
                            <th>Email</th>
                            <th>Preferred Location</th>
                            <th>Property Budget</th>
                            <th>Registration Timestamp</th>
                            @foreach ($data['stations'] as $station)
                            <th>{!! strtoupper($station['name']) !!}</th>
                            @endforeach
                            @foreach ($data['developers'] as $developer)
                                <th>{{ strtoupper($developer['name']) }}</th>
                            @endforeach
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['users'] as $user)
                        <tr data-user-id="{{ $user->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $user->fname }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($user->locations as $location)
                                    <div>{{ $location }}</div>
                                @endforeach
                            </td>
                            <td>{{ $user->property_budget }}</td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->toDayDateTimeString() }}</td>
                            @foreach ($user['stations'] as $station)
                                <td class="text-sm mb-0 {{ $station['value'] ? 'text-success' : 'text-danger' }}">

                                    @if($station['id'] == 3 && $station['value'])

                                        {{-- Gift name --}}
                                        {{ optional($user->userGift->gift)->name ?? 'No Gift' }}

                                        <br>

                                        {{-- Gift created_at --}}
                                        <small class="text-muted">
                                            {{ optional($user->userGift)->created_at 
                                                ? \Carbon\Carbon::parse($user->userGift->created_at)->toDayDateTimeString()
                                                : '-' }}
                                        </small>

                                    @else

                                        {{ $station['value'] ? 'Yes' : 'No' }}

                                    @endif

                                </td>
                            @endforeach

                            @foreach ($user['developers_list'] ?? [] as $developer)
                                <td class="{{ $developer['value'] ? 'text-success' : 'text-danger' }}">
                                    {{ $developer['value'] ? 'Yes' : 'No' }}
                                </td>
                            @endforeach
                            <td class="button-delete">
                                @if($user->isProtectedAdmin())
                                    <button class="btn btn-secondary btn-sm btn-protected" disabled 
                                            title="This admin user is protected and cannot be deleted"
                                            data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="fa fa-lock"></i> Protected
                                    </button>
                                @else
                                    <button class="btn btn-danger btn-sm delete-user-btn" data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}">Delete</button>
                                @endif
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

<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Name Export File</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="exportFileName" class="form-control" placeholder="Enter file name">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button id="confirmExport" class="btn btn-primary">Export</button>
            </div>
        </div>
    </div>
</div>

<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">

<!-- Include DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">

<style>
    .sticky-action {
        position: sticky !important;
        left: 0 !important;
        background-color: white !important;
        z-index: 10 !important;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1) !important;
    }

    /* Sticky header for the name column */
    thead .sticky-action {
        background-color: #f8f9fa !important;
        z-index: 11 !important;
    }

    /* Ensure the table wrapper allows horizontal scrolling */
    .custom-table {
        overflow-x: auto !important;
    }

    /* Make sure the sticky column has proper border */
    .sticky-action::after {
        content: '';
        position: absolute;
        right: -1px;
        top: 0;
        bottom: 0;
        width: 1px;
        background-color: #dee2e6;
    }

    /* Ensure minimum width for the name column */
    .sticky-action {
        min-width: 120px !important;
    }

    /* Protected admin button styling */
    .btn-protected {
        background-color: #6c757d !important;
        border-color: #6c757d !important;
        cursor: not-allowed !important;
        opacity: 0.7 !important;
    }

    .btn-protected:hover {
        background-color: #5c636a !important;
        border-color: #565e64 !important;
        transform: none !important;
    }

    /* Admin badge styling */
    .badge.bg-warning {
        font-size: 0.65rem !important;
        padding: 0.25rem 0.4rem !important;
    }

    .badge .fa-crown {
        font-size: 0.6rem !important;
    }

    /* ===== DATATABLE TOOLBAR ALIGNMENT ===== */

    /* Toolbar row: flex-wrap so it wraps on mobile, mb-3 gap from table */
    .dt-layout-row:first-child,
    div.dataTables_wrapper div.dt-buttons {
        display: flex !important;
        flex-wrap: wrap !important;
        align-items: center !important;
        gap: 6px !important;
        margin-bottom: 12px !important;
    }

    /* Preferred Location column — wider with wrapping */
    #customer-table .preferred-location-col {
        min-width: 220px !important;
        white-space: normal !important;
        word-break: break-word !important;
    }

    /* Increase table body font size */
    #customer-table td {
        font-size: 14px !important;
    }

    #customer-table th {
        font-size: 13px !important;
    }

    /* Uniform style for all toolbar buttons (export + date filter) */
    .dt-buttons .dt-button:not(.d-none),
    .dt-buttons .btn:not(.d-none) {
        height: 30px !important;
        line-height: 1 !important;
        padding: 0 12px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        border-radius: 4px !important;
        border: 1px solid #dee2e6 !important;
        background: #fff !important;
        color: #344767 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        white-space: nowrap !important;
        box-shadow: 0 1px 2px rgba(0,0,0,.08) !important;
        transition: background .15s !important;
        margin-right: 6px !important;
        margin-bottom: 0 !important;
        cursor: pointer !important;
    }

    .dt-buttons .dt-button:not(.d-none):hover,
    .dt-buttons .btn:not(.d-none):hover { background: #f1f3f5 !important; }

    /* CSV icon color */
    .dt-buttons .btn-info i { color: #17a2b8; }

    /* Excel icon color */
    .dt-buttons .btn-success i { color: #28a745; }

    /* ===== DATE FILTER DROPDOWN ===== */
    #date-filter-wrapper {
        position: relative;
        display: inline-block;
        vertical-align: middle;
    }

    #date-filter-btn {
        height: 30px;
        line-height: 1;
        padding: 0 12px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #344767;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        box-shadow: 0 1px 2px rgba(0,0,0,.08);
        transition: background .15s;
    }

    #date-filter-btn:hover { background: #f1f3f5; }

    #date-filter-btn.active {
        background: #e8f4ff;
        border-color: #5e72e4;
        color: #5e72e4;
    }

    #date-filter-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        z-index: 9999;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0,0,0,.12);
        padding: 16px;
        min-width: 300px;
    }

    #date-filter-dropdown.show { display: block; }

    #date-filter-dropdown label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        color: #8392a5;
        margin-bottom: 4px;
        display: block;
    }

    #date-filter-dropdown input[type=date] {
        font-size: 13px;
        border-radius: 5px;
        border: 1px solid #dee2e6;
        padding: 5px 8px;
        width: 100%;
        color: #344767;
    }

    .date-filter-actions {
        display: flex;
        gap: 8px;
        margin-top: 14px;
    }

    .date-filter-actions button {
        flex: 1;
        font-size: 12px;
        padding: 6px 0;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }

    #applyDateFilter  { background: #5e72e4; color: #fff; }
    #applyDateFilter:hover  { background: #4a5fd4; }
    #resetDateFilter  { background: #f1f3f5; color: #344767; }
    #resetDateFilter:hover  { background: #e2e6ea; }
</style>
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
    $('#customer-table').hide();

    var permissionName = "{{ $permission }}";
    let exportType = 'export';

    const today = new Date().toISOString().split('T')[0];

    /* ===================== DATATABLE INIT ===================== */
    var table = $('#customer-table').DataTable({
        responsive: true,
        dom: "<'row'<'col-12'B>>" +
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
            },
            {
                targets: 3, // Preferred Location
                width: '220px',
                className: 'preferred-location-col'
            }
        ],

        initComplete: function () {
            var api = this.api();
            $('.loader-container').hide();
            $('#customer-table').show();

            // Inject calendar icon button + dropdown beside export buttons
            $('.dt-buttons').append(`
                <div id="date-filter-wrapper">
                    <button id="date-filter-btn" title="Filter by date">
                        <i class="fa fa-calendar-alt"></i>
                        <span id="date-filter-label">Today</span>
                        <i class="fa fa-chevron-down" style="font-size:10px;opacity:.6;"></i>
                    </button>
                    <div id="date-filter-dropdown">
                        <div class="mb-2">
                            <label>From</label>
                            <input type="date" id="startDate">
                        </div>
                        <div>
                            <label>To</label>
                            <input type="date" id="endDate">
                        </div>
                        <div class="date-filter-actions">
                            <button id="applyDateFilter"><i class="fa fa-check me-1"></i> Apply</button>
                            <button id="resetDateFilter"><i class="fa fa-times me-1"></i> Reset</button>
                        </div>
                    </div>
                </div>
            `);

            // Set today as default and apply initial filter
            $('#startDate').val(today);
            $('#endDate').val(today);
            api.draw();

            // Toggle dropdown
            $(document).on('click', '#date-filter-btn', function (e) {
                e.stopPropagation();
                $('#date-filter-dropdown').toggleClass('show');
                $(this).toggleClass('active');
            });

            // Close on outside click
            $(document).on('click', function (e) {
                if (!$(e.target).closest('#date-filter-wrapper').length) {
                    $('#date-filter-dropdown').removeClass('show');
                    $('#date-filter-btn').removeClass('active');
                }
            });

            function updateFilterLabel() {
                const s = $('#startDate').val();
                const e = $('#endDate').val();
                if (!s && !e) { $('#date-filter-label').text('All dates'); return; }
                const fmt = d => d ? new Date(d + 'T00:00:00').toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'}) : '—';
                $('#date-filter-label').text(s === e ? fmt(s) : fmt(s) + ' – ' + fmt(e));
            }

            // Apply
            $(document).on('click', '#applyDateFilter', function () {
                const start = $('#startDate').val();
                const end   = $('#endDate').val();
                if (start && end && end < start) {
                    alert('"To date" cannot be earlier than "From date".');
                    return;
                }
                table.draw();
                updateFilterLabel();
                $('#date-filter-dropdown').removeClass('show');
                $('#date-filter-btn').removeClass('active');
            });

            // Reset
            $(document).on('click', '#resetDateFilter', function () {
                $('#startDate').val('');
                $('#endDate').val('');
                table.draw();
                updateFilterLabel();
                $('#date-filter-dropdown').removeClass('show');
                $('#date-filter-btn').removeClass('active');
            });

            updateFilterLabel();

            // Inject custom search input aligned with the buttons
            $('.dt-buttons').append(`
                <div id="custom-search-wrapper" style="
                    display:inline-flex;
                    align-items:center;
                    height:30px;
                    border:1px solid #dee2e6;
                    border-radius:4px;
                    background:#fff;
                    box-shadow:0 1px 2px rgba(0,0,0,.08);
                    overflow:hidden;
                    margin-left:6px;
                ">
                    <span style="padding:0 8px;color:#8392a5;font-size:12px;display:flex;align-items:center;">
                        <i class="fa fa-search"></i>
                    </span>
                    <input id="custom-dt-search" type="text" placeholder="Search..."
                        style="
                            border:none;
                            outline:none;
                            height:100%;
                            font-size:13px;
                            color:#344767;
                            background:transparent;
                            min-width:200px;
                            padding:0 10px 0 0;
                        ">
                </div>
            `);

            // Wire custom search input to DataTable
            $('#custom-dt-search').on('input', function () {
                api.search(this.value).draw();
            });

            // Apply mobile layout via inline styles (overrides all CSS specificity)
            function applyToolbarLayout() {
                var isMobile = window.innerWidth <= 768;
                var $buttons = $('.dt-buttons');
                var $csvBtn  = $buttons.find('.btn-info');
                var $xlsBtn  = $buttons.find('.btn-success');
                var $dateW   = $('#date-filter-wrapper');
                var $dateBtn = $('#date-filter-btn');
                var $searchW = $('#custom-search-wrapper');
                var $searchI = $('#custom-dt-search');

                if (isMobile) {
                    $buttons.css({ display:'flex', flexWrap:'wrap', width:'100%', gap:'0', rowGap:'8px', marginBottom:'12px' });
                    $csvBtn.css({ flex:'0 0 50%', width:'50%', margin:'0', paddingRight:'4px', boxSizing:'border-box', justifyContent:'center' });
                    $xlsBtn.css({ flex:'0 0 50%', width:'50%', margin:'0', paddingLeft:'4px', boxSizing:'border-box', justifyContent:'center' });
                    $dateW.css({ flex:'0 0 100%', width:'100%', margin:'0' });
                    $dateBtn.css({ width:'100%', justifyContent:'space-between', boxSizing:'border-box' });
                    $searchW.css({ flex:'0 0 100%', width:'100%', marginLeft:'0', boxSizing:'border-box', display:'flex' });
                    $searchI.css({ minWidth:'0', width:'100%' });
                } else {
                    $buttons.css({ display:'flex', flexWrap:'', width:'', gap:'', rowGap:'', marginBottom:'12px' });
                    $csvBtn.css({ flex:'', width:'', margin:'', paddingRight:'', boxSizing:'', justifyContent:'' });
                    $xlsBtn.css({ flex:'', width:'', margin:'', paddingLeft:'', boxSizing:'', justifyContent:'' });
                    $dateW.css({ flex:'', width:'', margin:'' });
                    $dateBtn.css({ width:'', justifyContent:'', boxSizing:'' });
                    $searchW.css({ flex:'', width:'', marginLeft:'6px', boxSizing:'', display:'inline-flex' });
                    $searchI.css({ minWidth:'200px', width:'' });
                }
            }

            applyToolbarLayout();
            $(window).on('resize', applyToolbarLayout);

            // Fix pagination & info font size directly
            setTimeout(function () {
                // DataTables info (e.g. "Showing 1 to 10 of 50 entries")
                $('[class*="dataTables_info"], [class*="dt-info"]').css('font-size', '13px');
                // Pagination buttons
                $('[class*="paginate_button"], [class*="dt-paging-button"], [class*="dataTables_paginate"] a, [class*="dataTables_paginate"] span').css('font-size', '13px');
            }, 100);

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

        // Registration Timestamp is always at column index 5
        let rowDate = new Date(data[5]);

        // Convert row date to YYYY-MM-DD (DATE ONLY)
        let rowDateOnly = rowDate.getFullYear() + '-' +
            String(rowDate.getMonth() + 1).padStart(2, '0') + '-' +
            String(rowDate.getDate()).padStart(2, '0');

        if (!start && !end) return true;

        if (start && rowDateOnly < start) return false;
        if (end && rowDateOnly > end) return false;

        return true;
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

    /* ===================== ROW CLICK REDIRECT ===================== */
    $('#customer-table tbody').on('click', 'tr', function (e) {
        if ($(e.target).closest('.delete-user-btn').length) return;

        var userId = $(this).data('user-id');
        window.location.href = "{{ route('userData', ['user' => ':userId']) }}"
            .replace(':userId', userId);
    });

    /* ===================== DELETE BUTTON ===================== */
    $('#customer-table tbody').on('click', '.delete-user-btn', function (e) {
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

<!-- <script>
    // Show the loader
    $('.loader-container').show();
    $('#customer-table').hide();

    var permissionName = "{{ $permission }}";
    var table = $('#customer-table').DataTable({
        responsive: true,
        dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6 d-flex justify-content-end'f>>" +
            "<'row'<'col-sm-12 table-responsive custom-table' tr>>" +
            "<'row'<'d-flex justify-content-start col-sm-12 col-md-6 mt-3'i><'col-sm-12 col-md-6 mt-3 d-flex justify-content-end'p>>",
        buttons: [{
            extend: 'csv',
            text: '<i class="fa fa-file-csv"></i> CSV',
            className: 'btn btn-info'
        }, {
            extend: 'excel',
            text: '<i class="fa fa-file-excel"></i> Excel',
            className: 'btn btn-success'
        }, {
            extend: 'pdf',
            text: '<i class="fa fa-file-pdf"></i> PDF',
            className: 'btn btn-danger'
        }],
        order: [
            [0, 'desc']
        ],
        columnDefs: [{
            orderable: false,
            targets: -1
        } // Disable sorting on last column (Action)
        ],
        initComplete: function (settings, json) {
            $('.loader-container').hide();
            $('#customer-table').show();
            
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    });

    $.fn.dataTable.ext.search.push(function (settings, data) {
        let start = $('#startDate').val();
        let end = $('#endDate').val();

        // Timestamp column (2nd last column)
        let rowDate = new Date(data[data.length - 2]);

        if (!start && !end) return true;

        start = start ? new Date(start) : null;
        end = end ? new Date(end) : null;

        return (!start || rowDate >= start) && (!end || rowDate <= end);
    });


    $('#customer-table tbody').on('click', 'tr', function (e) {
        // Prevent redirect if the clicked target is inside a delete button
        console.log('cliked');
        if ($(e.target).closest('.delete-user-btn').length) {
            return;
        }

        var userId = $(this).data('user-id');

        window.location.href = "{{ route('userData', ['user' => ':userId']) }}".replace(
            ':userId', userId);
    });

    // Use event delegation for delete button
    $('#customer-table tbody').on('click', '.delete-user-btn', function (e) {
        e.stopPropagation(); // Prevent row click
        const userId = $(this).data('user-id');
        const userName = $(this).data('user-name');

        let deleteUrl = @json(route('users.destroy', ['id' => ':id']));
    deleteUrl = deleteUrl.replace(':id', userId);

    $('#deleteUserForm').attr('action', deleteUrl);
    $('#deleteUserName').text(userName);
    $('#deleteUserModal').modal('show');
            });

            
</script> -->

@if(session('success'))
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div id="successToast" class="toast align-items-center bg-success text-white border-0 fade show" role="alert"
        aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <i class="fa fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toastEl = document.getElementById('successToast');
        if (toastEl) {
            var toast = new bootstrap.Toast(toastEl, { delay: 3000 });
            toast.show();
        }
    });
</script>
@endif

@if(session('error'))
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
    <div id="errorToast" class="toast align-items-center bg-danger text-white border-0 fade show" role="alert"
        aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
                <i class="fa fa-exclamation-circle me-2"></i>
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var toastEl = document.getElementById('errorToast');
        if (toastEl) {
            var toast = new bootstrap.Toast(toastEl, { delay: 5000 });
            toast.show();
        }
    });
</script>
@endif
@endsection