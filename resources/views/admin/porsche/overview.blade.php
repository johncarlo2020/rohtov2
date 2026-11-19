@extends('layouts.admin')

@section('content')
    {{-- Top 4 KPI Cards in 2-Color Site Palette (Primary Blue & Dark Navy) --}}
    <div class="row pt-2">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Participants</p>
                                <h5 class="font-weight-bolder">
                                    {{ number_format($totalRegistrations) }}
                                </h5>
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
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Completed Quizzes</p>
                                <h5 class="font-weight-bolder">
                                    {{ number_format($totalAttempts) }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-dark shadow-dark text-center rounded-circle">
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
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Avg Entries / User</p>
                                <h5 class="font-weight-bolder">
                                    {{ $avgAttemptsPerParticipant }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                <i class="fa-solid fa-chart-line text-lg opacity-10" aria-hidden="true"></i>
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
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Result A Share</p>
                                <h5 class="font-weight-bolder">
                                    {{ $resultPercentages['A'] }}%
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-dark shadow-dark text-center rounded-circle">
                                <i class="fa-solid fa-circle-check text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Results Breakdown Cards Row (Clean 2-Color Palette) --}}
    <div class="row mt-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <p class="mb-2 font-weight-bold">Personality Results</p>
            <form method="GET" action="{{ route('admin.overview') }}" class="d-flex align-items-center gap-2 mb-2">
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control form-control-sm w-auto">
                <span class="text-xs text-muted">to</span>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control form-control-sm w-auto">
                <button type="submit" class="btn btn-sm btn-primary mb-0 py-1 px-3">Filter</button>
                @if($dateFrom || $dateTo)
                    <a href="{{ route('admin.overview') }}" class="btn btn-sm btn-outline-secondary mb-0 py-1 px-2">Reset</a>
                @endif
            </form>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between rounded p-3">
                    <div class="d-flex align-items-center w-100">
                        <div class="icon-stations me-3 d-flex align-items-center justify-content-center bg-gradient-primary text-white rounded p-2" style="width: 48px; height: 48px;">
                            <span class="font-weight-bold fs-5">A</span>
                        </div>
                        <div class="d-flex flex-column">
                            <h6 class="mb-1 text-sm text-primary">ENDLESS POSSIBILITIES</h6>
                            <span class="text-xs">
                                <span class="font-weight-bold">{{ number_format($resultCounts['A']) }} Entries</span> ({{ $resultPercentages['A'] }}%)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between rounded p-3">
                    <div class="d-flex align-items-center w-100">
                        <div class="icon-stations me-3 d-flex align-items-center justify-content-center bg-gradient-dark text-white rounded p-2" style="width: 48px; height: 48px;">
                            <span class="font-weight-bold fs-5">B</span>
                        </div>
                        <div class="d-flex flex-column">
                            <h6 class="mb-1 text-sm text-dark">UNFORGETTABLE EXPERIENCE</h6>
                            <span class="text-xs">
                                <span class="font-weight-bold">{{ number_format($resultCounts['B']) }} Entries</span> ({{ $resultPercentages['B'] }}%)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between rounded p-3">
                    <div class="d-flex align-items-center w-100">
                        <div class="icon-stations me-3 d-flex align-items-center justify-content-center border border-2 border-primary text-primary bg-white rounded p-2" style="width: 48px; height: 48px;">
                            <span class="font-weight-bold fs-5">C</span>
                        </div>
                        <div class="d-flex flex-column">
                            <h6 class="mb-1 text-sm text-primary">MEMORY WORTH KEEPING</h6>
                            <span class="text-xs">
                                <span class="font-weight-bold">{{ number_format($resultCounts['C']) }} Entries</span> ({{ $resultPercentages['C'] }}%)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Original Highcharts Row matching dashboardadmin.blade.php --}}
    <div class="row mt-1">
        <div class="col-lg-6 mb-lg-3 mb-3">
            <div class="card z-index-2 h-100">
                <div class="card-body p-3">
                    <figure class="highcharts-figure">
                        <div id="container2"></div>
                    </figure>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-lg-3 mb-3">
            <div class="card z-index-2 h-100">
                <div class="card-body card-with-filter p-3">
                    <figure class="highcharts-figure">
                        <div id="container"></div>
                    </figure>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Completed Entries Table in Original Dashboard Table Style --}}
    <div class="row mt-1">
        <div class="col-lg-12 mb-lg-3 mb-4">
            <div class="card h-100 p-3 mb-3">
                <div class="card-header pb-0 px-3 pt-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-2 card-header-text">Recent Completed Entries</h6>
                        <a href="{{ route('admin.entries') }}" class="btn btn-sm btn-link text-primary mb-0 p-0">View All</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center border mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Participant</th>
                                <th class="text-center">Attempt</th>
                                <th class="text-center">Answers</th>
                                <th class="text-center">Result</th>
                                <th class="text-center">Completed At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentEntries as $entry)
                                <tr>
                                    <td>
                                        <div class="ms-3">
                                            <h6 class="text-sm mb-0">#{{ $entry->id }}</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ms-2">
                                            <h6 class="text-sm mb-0">{{ $entry->registration?->full_name ?? 'Participant #' . $entry->porsche_registration_id }}</h6>
                                            <p class="text-xs text-muted mb-0">{{ $entry->registration?->email ?? '-' }}</p>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">#{{ $entry->attempt_number }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="font-monospace text-xs fw-bold">{{ $entry->answers_string }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($entry->result_type === 'A')
                                            <span class="badge bg-gradient-primary">Result A</span>
                                        @elseif($entry->result_type === 'B')
                                            <span class="badge bg-gradient-dark">Result B</span>
                                        @else
                                            <span class="badge bg-light text-primary border border-primary">Result C</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="text-xs font-weight-bold">{{ $entry->completed_at ? $entry->completed_at->format('Y-m-d H:i') : '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted text-sm">
                                        No quiz entries recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Original Highcharts Scripts from dashboardadmin.blade.php --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 310px;
            max-width: 100%;
            margin: 0;
        }
    </style>

    <script>
        var labels = [];
        var data = [];
        var chart = @json($usersDaily);

        Object.keys(chart).forEach(function(date, index) {
            var dateObj = new Date(date);
            var formattedDate = dateObj.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric'
            });
            labels.push(formattedDate);
            data.push(chart[date]);
        });

        // Chart 1: Customers Overview in Site Blue Palette (#285CA9)
        Highcharts.chart('container2', {
            chart: {
                type: 'spline',
                height: 400
            },
            title: {
                text: 'Customers Overview',
                align: 'left'
            },
            yAxis: {
                title: {
                    text: 'Registrations'
                }
            },
            xAxis: {
                categories: labels,
                accessibility: {
                    rangeDescription: labels.join(', ')
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            series: [{
                name: 'Registration',
                data: data,
                color: '#285CA9'
            }],
            plotOptions: {
                series: {
                    fill: true,
                    borderColor: '#285CA9',
                    backgroundColor: 'rgba(40, 92, 169, 0.15)',
                    pointBackgroundColor: '#285CA9',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            return this.y;
                        },
                        verticalAlign: 'bottom',
                        crop: false,
                        overflow: 'none'
                    }
                }
            },
            credits: {
                enabled: false
            },
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });

        // Chart 2: Hourly Customer Registrations by Date in 2-Color Palette
        var registrationsPerHour = @json($registrationsPerHour);
        var hours = Object.keys(registrationsPerHour).sort(function(a, b) {
            var timeA = new Date('1970/01/01 ' + a.replace(/([ap]m)/, ' $1'));
            var timeB = new Date('1970/01/01 ' + b.replace(/([ap]m)/, ' $1'));
            return timeA - timeB;
        });
        var allDates = [];

        for (var hour in registrationsPerHour) {
            if (registrationsPerHour.hasOwnProperty(hour)) {
                registrationsPerHour[hour].forEach(function(item) {
                    if (allDates.indexOf(item.date) === -1) {
                        allDates.push(item.date);
                    }
                });
            }
        }
        allDates.sort();

        // Colors palette: Site Primary Blue (#285CA9) and Navy/Dark (#000022), with lighter blue accents
        var paletteColors = ['#285CA9', '#000022', '#4b7bbd', '#1f2937', '#7fa7db'];

        var seriesData = allDates.map(function(date, idx) {
            var dataPoints = hours.map(function(hour) {
                var registration = registrationsPerHour[hour].find(r => r.date === date);
                return registration ? registration.registrations : 0;
            });
            return {
                name: date,
                data: dataPoints,
                color: paletteColors[idx % paletteColors.length]
            };
        });

        Highcharts.chart('container', {
            chart: {
                type: 'column',
                height: 400
            },
            title: {
                text: 'Hourly Customer Registrations by Date',
                align: 'left'
            },
            xAxis: {
                categories: hours,
                crosshair: true,
                accessibility: {
                    description: 'Hours'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Number of Registrations'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y} registrations</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            if (this.y > 0) {
                                return this.y;
                            }
                            return null;
                        }
                    }
                }
            },
            series: seriesData,
            credits: {
                enabled: false
            },
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }
        });
    </script>
@endsection
