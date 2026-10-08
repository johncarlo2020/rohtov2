@extends('layouts.admin')

@section('content')
    {{-- Customer Metric Summary Cards --}}
    <div class="row pt-2 mb-3">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-xs mb-1 text-uppercase font-weight-bold text-muted">Total Customers</p>
                                <h4 class="font-weight-bolder mb-0">
                                    {{ $data['usersCount'] }}
                                </h4>
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
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-xs mb-1 text-uppercase font-weight-bold text-muted">Today's Customer</p>
                                <h4 class="font-weight-bolder mb-0">
                                    {{ $data['userToday'] }}
                                </h4>
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
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-xs mb-1 text-uppercase font-weight-bold text-muted">Completion Rate</p>
                                <h4 class="font-weight-bolder mb-0">
                                    {{ $data['percentage'] }}%
                                </h4>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape text-center rounded-circle text-white" style="background: linear-gradient(310deg, #11cdef 0%, #1171ef 100%) !important; box-shadow: 0 4px 6px -1px rgba(17, 205, 239, 0.4), 0 2px 4px -1px rgba(17, 205, 239, 0.2);">
                                <i class="fa-solid fa-percent text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-xs mb-1 text-uppercase font-weight-bold text-muted">Customers Finished</p>
                                <h4 class="font-weight-bolder mb-0">
                                    {{ $data['completedUsers'] }}
                                </h4>
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

    {{-- Stations Overview --}}
    @if(isset($data['stationList']) && count($data['stationList']) > 0)
    <div class="row mb-3">
        <div class="col-12 mb-2">
            <h6 class="text-secondary font-weight-bolder ps-1 mb-0" style="font-size: 0.95rem;">Stations</h6>
        </div>
        @foreach ($data['stationList'] as $station)
            <div class="col-xl-4 col-md-6 col-12 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="me-3 flex-shrink-0" style="width: 52px; height: 52px; border-radius: 8px; overflow: hidden; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border: 1px solid #edf2f7;">
                            <img src="{{ asset('images/station/ST' . $station->id . '.webp') }}" 
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=200&q=60';"
                                 alt="{{ $station->name }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="text-xs font-weight-bold mb-1 text-uppercase text-dark" style="letter-spacing: 0.5px;">{{ $station->name }}</h6>
                            <span class="text-xs text-secondary font-weight-bold">{{ $station->completed_users_count }} Users Completed</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif





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
    <div class="row mt-1 d-none">
        <div class="col-lg-6 mb-lg-3 mb-4">
            <div class="card h-100 p-3 mb-3">
                <div class="card-header pb-0 px-3 pt-0">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2 card-header-text">Customer</h6>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center border">
                        <thead>
                            <tr>
                                <th >ID</th>
                                <th>Name</th>
                                <th>Station completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['users'] as $user)
                                <tr>
                                    <td>
                                        <div class="">
                                            <div class="ms-4">
                                                <h6 class="text-sm mb-0">{{ $user->id }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td >
                                        <div class="">
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">Name</p>
                                                <h6 class="text-sm mb-0">{{ ucfirst($user->fname) }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="station-icon-wrapper">
                                            @foreach ($user['stations'] as $station)
                                                <div class="text-center">
                                                    <img src="{{ asset("images/station/ST{$station['id']}.webp") }}"
                                                        alt="{{ $station['name'] }}"
                                                        title="{{ $station['name'] }}"
                                                        class="station-image table-station-image {{ $station['value'] ? 'border-success' : 'border-secondary' }}"
                                                        style="opacity: {{ $station['value'] ? '1' : '0.4' }};"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom" />
                                                </div>
                                            @endforeach
                                            <div class="completed-count d-flex justify-content-center align-items-center gap-2">
                                                <p class="m-0 p-0">Completed  <span>{{ $user->completed_count }}</span></p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>





    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Chart.js 3.x -->
    <!-- Chart.js 2.x -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
    <!-- Chart.js Datalabels plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/canvas2image/0.1.0/canvas2image.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <style>
        .highcharts-data-table table {
            border-collapse: collapse;
            border-spacing: 0;
            background-color: transparent;
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }

        .highcharts-data-table th,
        .highcharts-data-table td {
            border: 1px solid #dee2e6;
            padding: .75rem;
            vertical-align: top;
        }

        .highcharts-data-table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .highcharts-data-table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        .highcharts-data-table .highcharts-table-caption {
            caption-side: bottom;
            padding-top: .75rem;
            padding-bottom: .75rem;
            color: #6c757d;
            text-align: left;
        }
    </style>

    <script>
        var labels = [];
        var data = [];
        var permissionName = "{{ $permission }}";

        var chart = @json($data['usersDaily']);
        console.log(chart);

        Object.keys(chart).forEach(function(date, index) {
            var dateStr = date ? (date.indexOf('T') !== -1 ? date : date.replace(/ /g, 'T') + (date.length === 10 ? 'T00:00:00' : '')) : '';
            var dateObj = new Date(dateStr);
            var formattedDate = isNaN(dateObj.getTime()) ? date : dateObj.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric'
            });
            labels.push(formattedDate);
            data.push(chart[date]); // Push the count for the corresponding date
        });

        function parseTimeToMinutes(timeStr) {
            if (!timeStr) return 0;
            var str = timeStr.trim().toLowerCase();
            var isPm = str.indexOf('pm') !== -1;
            var isAm = str.indexOf('am') !== -1;
            str = str.replace(/[ap]m/g, '').trim();
            var parts = str.split(':');
            var hours = parseInt(parts[0], 10) || 0;
            var minutes = parts[1] ? parseInt(parts[1], 10) : 0;
            if (isPm && hours < 12) hours += 12;
            if (isAm && hours === 12) hours = 0;
            return hours * 60 + minutes;
        }

        var registrationsPerHour = @json($data['registrationsPerHour']);
        var hours = Object.keys(registrationsPerHour).sort(function(a, b) {
            return parseTimeToMinutes(a) - parseTimeToMinutes(b);
        });
        var allDates = [];

        // Get all unique dates
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

        // Prepare series data
        var seriesData = allDates.map(function(date) {
            var dataPoints = hours.map(function(hour) {
                var registration = registrationsPerHour[hour].find(r => r.date === date);
                return registration ? registration.registrations : 0;
            });
            return {
                name: date,
                data: dataPoints
            };
        });

        var high = Highcharts.chart('container', {
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

        var high2 = Highcharts.chart('container2', {
            chart: {
                type: 'spline', // Changed from 'line' to 'spline' for curved lines
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
                categories: labels, // Use labels2 as xAxis categories
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
                data: data
            }],
            plotOptions: {
                series: {
                    fill: true, // enable area under the line
                    borderColor: '#3b82f6', // blue line
                    backgroundColor: 'rgba(59, 130, 246, 0.2)', // shaded area
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            return this.y; // Show the count at each dot
                        },
                        verticalAlign: 'bottom',
                        crop: false,
                        overflow: 'none'
                    }
                }
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

        // Utility to generate a random color in hex format
        function getRandomColor() {
            return '#' + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0');
        }
        // Assign random colors to each data point
        function assignRandomColors(data) {
            return data.map(function(point) {
                return Object.assign({}, point, {
                    color: getRandomColor()
                });
            });
        }
        // Shared pie chart config
        function getPieChartConfig({
            renderTo,
            title,
            data
        }) {
            return {
                chart: {
                    renderTo: renderTo,
                    type: 'pie',
                    height: 400
                },
                title: {
                    text: title,
                    align: 'left'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y}</b> ({point.percentage:.1f}%)'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                legend: {
                    enabled: true,
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle',
                    maxHeight: 500,
                    navigation: {
                        enabled: true
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.y} ({point.percentage:.1f}%)',
                            distance: 20
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    name: 'Count',
                    colorByPoint: true,
                    data: assignRandomColors(data)
                }],
                credits: {
                    enabled: false
                },
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 700
                        },
                        chartOptions: {
                            chart: {
                                height: 300
                            },
                            legend: {
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom',
                                maxHeight: 100
                            },
                            plotOptions: {
                                pie: {
                                    dataLabels: {
                                        distance: 10
                                    }
                                }
                            }
                        }
                    }]
                }
            };
        }

        (function() {
            // Pie chart for raceChart only
            var races = @json($data['race']);
            var raceData = races.map(function(item) {
                return {
                    name: item.race || item.name || item.label || '',
                    y: item.count || 0
                };
            });
            Highcharts.chart(getPieChartConfig({
                renderTo: 'raceChart',
                title: 'Race Distribution',
                data: raceData
            }));


            // Highcharts.chart(getPieChartConfig({
            //     renderTo: 'findEventChart',
            //     title: 'How did you find this event?',
            //     data: findEventData
            // }));
        })();

    </script>
@endsection
