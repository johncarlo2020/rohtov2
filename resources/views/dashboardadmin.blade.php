@extends('layouts.admin')

@section('content')
    <div class="row pt-2">
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
    <div class="row mt-4">
        @foreach ($data['stations'] as $station)
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex justify-content-between mb-2 rounded  p-3 ">
                        <div class="d-flex align-items-center w-100">
                            <div class="icon-stations">
                                <img class="" src="{{ asset("images/hadalabobabies/station{$station['id']}.webp") }}" alt="Station Image">
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-1 text-dark text-sm">{{ $station['name'] }}</h6>
                                <span class="text-xs">Average Time : <span
                                        class="font-weight-bold">{{ $station['average_timespent'] }}
                                        minutes</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row mt-4">
        <div class="col-lg-6 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
                <div class="card-body p-3">
                    <figure class="highcharts-figure">
                        <div id="container2"></div>
                    </figure>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
                <div class="card-body card-with-filter p-3">
                    <select class="form-control form-control-sm" id="date-format-select">
                        @foreach ($data['dates'] as $key => $date)
                            <option value="{{ $date['date'] }}">{{ $date['date'] }}</option>
                        @endforeach
                    </select>
                    <figure class="highcharts-figure">
                        <div id="container"></div>
                    </figure>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-4">
            <div class="card card h-100 mb-3">
                <div class="card-body p-3">
                    <div id="countriesChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card h-100 mb-3">
                <div class="card-body p-3">
                    <div id="findEventChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card h-100 mb-3">
                <div class="card-body p-3">
                    <div id="socialMediaChart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-4">
            <div class="card card h-100 mb-3">
                <div class="card-body p-3">
                    <div id="existingMemberChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card h-100">
                <div class="card-body p-3">
                    <div id="appealBarChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card h-100">
                <div class="card-body p-3">
                    <div id="ageChart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4">
            <div class="card card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2">Customer</h6>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center ">
                        <tbody>
                            @foreach ($data['users'] as $user)
                                <tr>
                                    <td class="w-5">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="ms-4">
                                                <h6 class="text-sm mb-0">{{ $user->id }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-10">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">Name</p>
                                                <h6 class="text-sm mb-0">{{ $user->fname }} {{ $user->lname }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>
                                    @foreach ($user['stations'] as $station)
                                        <td>
                                            <div class="text-center">
                                                <p class="text-xs font-weight-bold mb-0">{{ $station['name'] }}
                                                </p>
                                                <h6
                                                    class="text-sm mb-0 {{ $station['value'] ? 'text-success' : 'text-danger' }}">
                                                    {{ $station['value'] ? 'Yes' : 'No' }}
                                                </h6>

                                            </div>
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


    <script>
        var labels = [];
        var labels2 = [];

        var data = [];
        var data2 = [];
        var chart2;
        var selectedDate = $('#date-format-select').val();

        // Listen for change event on select element
        $('#date-format-select').change(function() {
            selectedDate = $(this).val(); // Get selected date

            // Assuming $data['registrationsPerHour'] is an associative array where keys are dates
            // and values are arrays of registration data
            var newChart = @json($data['registrationsPerHour']);


            // Assuming newChart contains an array of registration data
            console.log(selectedDate);
            newData

            var newLabel = [];
            var newData = [];


            // Assuming 'newChart' is in the format required by Chart.js
            newChart[selectedDate].forEach((dataPoint) => {
                newLabel.push(dataPoint
                    .hour);
                newData.push(dataPoint
                    .registrations);

            });

            high.xAxis[0].setCategories(newLabel);

            high.series[0].setData(newData);
            high.setTitle({
                text: 'Customers per Hour on ' + selectedDate
            });
        });

        var permissionName = "{{ $permission }}";

        var chart = @json($data['usersDaily']);
        console.log(chart);

        var day1 = "{{ $data['dates'][0]['date'] }}";
        var chart2 = @json($data['registrationsPerHour'][$data['dates'][0]['date']]);

        Object.keys(chart).forEach(function(date, index) {
            var dateObj = new Date(date);
            var formattedDate = dateObj.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric'
            });
            labels.push(formattedDate);
            data.push(chart[date]); // Push the count for the corresponding date
        });


        chart2.forEach(function(obj) {
            // Log index

            // Push date and hour as label
            labels2.push(obj.hour);

            // Push registrations count
            data2.push(obj.registrations);
        });

        var high = Highcharts.chart('container', {
            chart: {
                type: 'column' // Set chart type to 'column'
            },
            title: {
                text: 'Customers per Hour',
                align: 'left'
            },
            yAxis: {
                title: {
                    text: 'Registrations'
                }
            },
            xAxis: {
                categories: labels2, // Use labels2 as xAxis categories
                accessibility: {
                    rangeDescription: labels2.join(', ') // Set range description using labels2
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            series: [{
                name: 'Registration',
                data: data2
            }],
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            return this.y; // Display the data value as the label
                        },
                        inside: false,
                        verticalAlign: 'top', // Position the label at the top of the column
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

        var high2 = Highcharts.chart('container2', {
            chart: {
                type: 'spline' // Changed from 'line' to 'spline' for curved lines
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
            data,
            height
        }) {
            return {
                chart: {
                    renderTo: renderTo,
                    type: 'pie',
                    height: height || 600
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
            var countries = @json($data['country']);
            var countryData = countries.map(function(item) {
                return {
                    name: item.country || item.name || item.label || '',
                    y: item.count || 0
                };
            });
            Highcharts.chart(getPieChartConfig({
                renderTo: 'countriesChart',
                title: 'Country Distribution',
                data: countryData
            }));

            var findData = @json($data['where']);
            var findEventData = findData.map(function(item) {
                return {
                    name: item.find || item.name || item.label || '',
                    y: item.count || 0
                };
            });
            Highcharts.chart(getPieChartConfig({
                renderTo: 'findEventChart',
                title: 'How did you find this event?',
                data: findEventData
            }));

            var socialMedia = @json($data['social_media']);
            var socialMediaData = Object.keys(socialMedia).map(function(platform) {
                return {
                    name: platform,
                    y: socialMedia[platform] || 0
                };
            });
            Highcharts.chart(getPieChartConfig({
                renderTo: 'socialMediaChart',
                title: 'Social Media Count',
                data: socialMediaData
            }));

            var existing = @json($data['existing']);
            var existingData = existing.map(function(item) {
                return {
                    name: item.existing || item.name || item.label || '',
                    y: item.count || 0
                };
            });
            Highcharts.chart(getPieChartConfig({
                renderTo: 'existingMemberChart',
                title: 'Existing Member?',
                data: existingData,
                height: 300
            }));

            var age = @json($data['age']);
            var ageData = age.map(function (item) {
                return {
                    name: item.dob || item.name || item.label || '',
                    y: item.count || 0
                };
            });
            Highcharts.chart(getPieChartConfig({
                renderTo: 'ageChart',
                title: 'Age Group?',
                data: ageData,
                height: 300
            }));
        })();

        (function() {
            var appeal = @json($data['appeal']);
            var appealLabels = appeal.map(function(item) {
                return item.appeal || item.name || item.label || '';
            });
            var appealCounts = appeal.map(function(item) {
                return item.count || 0;
            });
            var appealColors = appealLabels.map(function() {
                return getRandomColor();
            });
            Highcharts.chart('appealBarChart', {
                chart: {
                    type: 'bar',
                    height: 300
                },
                title: {
                    text: 'What Appeals the Most?',
                    align: 'left'
                },
                xAxis: {
                    categories: appealLabels,
                    title: {
                        text: 'Appeal'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Count',
                        align: 'high'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                tooltip: {
                    valueSuffix: ' people'
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                legend: {
                    enabled: false
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Count',
                    data: appealCounts,
                    colorByPoint: true,
                    colors: appealColors
                }],
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 700
                        },
                        chartOptions: {
                            chart: {
                                height: 300
                            },
                            xAxis: {
                                labels: {
                                    style: {
                                        fontSize: '10px'
                                    }
                                }
                            }
                        }
                    }]
                }
            });
        })();
    </script>
@endsection
