@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col d-block d-md-none mb-4">
            <div class="card">
                <a class="nav-link text-center d-flex justify-content-center align-items-center px-5 py-4 {{ request()->routeIs('scanner') ? 'active' : '' }}"
                    href="{{ route('scanner') }}">
                    <i class="ni ni-mobile-button text-warning text-sm opacity-10"></i>
                    <span class="nav-link-text ms-1">Scanner</span>
                </a>
            </div>
        </div>
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
                                <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
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
                                <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
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
                                <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
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
                                <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4 gap-3">
        @foreach ($data['stations'] as $station)
            <div class="col-2">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-between mb-2 rounded  p-2 ">
                        <div class="d-flex align-items-center w-100 gap-2">
                            <div class="icon-stations">
                                <img class="" src="{{ asset("files/station/{$station['id']}.webp") }}"
                                    alt="Station Image">
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
                <div class="card-body with-filter p-3">
                    <figure class="highcharts-figure">
                        <div id="container"></div>
                    </figure>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card card h-100 mb-3">
                <div class="card-body p-3">
                    <div id="countriesChart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-lg-0 mb-4">
            <div class="card h-100 p-3">
                <div class="card-header pb-0 px-3 pt-0">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-2 card-header-text">Customer</h6>
                    </div>
                </div>
                <div class="table-responsive h-100">
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
                                                <h6 class="text-sm mb-0">{{ $user->fname }} {{ $user->lname }}
                                                </h6>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="station-icon-wrapper">
                                                @foreach ($user['stations'] as $station)
                                            <div class="text-center">
                                                <img src="{{ asset('files/station/' . $station['id'] . '.webp') }}"
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

    {{-- <div class="row mt-4">
        <div class="col-lg-12 mb-lg-0 mb-4>
            <div class="card ">
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
    </div> --}}
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


        var permissionName = "{{ $permission }}";

        var chart = @json($data['usersDaily']);
        console.log(chart);


        Object.keys(chart).forEach(function(date, index) {
            var dateObj = new Date(date);
            var formattedDate = dateObj.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric'
            });
            labels.push(formattedDate);
            data.push(chart[date]); // Push the count for the corresponding date
        });
            var registrationsPerHour = @json($data['registrationsPerHour']);
        var hours = Object.keys(registrationsPerHour).sort(function(a, b) {
            var timeA = new Date('1970/01/01 ' + a.replace(/([ap]m)/, ' $1'));
            var timeB = new Date('1970/01/01 ' + b.replace(/([ap]m)/, ' $1'));
            return timeA - timeB;
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
            series: seriesData.map(function(series) {
                return {
                    name: series.name,
                    data: series.data.map(function(value) {
                        return value > 0 ? value : null;
                    })
                };
            }),
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
                type: 'line' // Set chart type to 'column'
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
                title: {
                    text: 'Dates'
                },
                accessibility: {
                    rangeDescription: labels.join(', ') // Set range description using labels2
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


        // function exportToPNG() {
        //     html2canvas(document.getElementById('chart-line'), {
        //         onrendered: function(canvas) {
        //             var link = document.createElement('a');
        //             link.href = canvas.toDataURL('image/png');
        //             link.download = 'chart.png';
        //             link.click();
        //         }
        //     });
        // }

        function exportToPDF() {
            var element = document.getElementById('chart-line');
            html2pdf()
                .from(element)
                .save('overview.pdf');
        }

        function exportToJPEG() {
            var canvas = document.getElementById('chart-line');
            var dataURL = canvas.toDataURL('image/jpeg');
            var link = document.createElement('a');
            link.href = dataURL;
            link.download = 'chart.jpeg';
            link.click();
        }

        function exportToPDF2() {
            var canvas = document.getElementById('chart-line2');
            var pageWidth = 595; // A4 page width in pixels
            var pageHeight = 842; // A4 page height in pixels

            // Set the canvas dimensions to fit within the page
            canvas.width = pageWidth;
            canvas.height = pageHeight;

            // Get the context of the canvas
            var ctx = canvas.getContext('2d');
            // Here you would draw your chart onto the canvas using the context 'ctx'
            // Ensure that the chart is drawn within the canvas dimensions

            // Convert the canvas to a data URL
            var dataURL = canvas.toDataURL();

            // Create a new image element
            var img = new Image();
            img.src = dataURL;

            // Create a new PDF document
            var pdf = new jsPDF('p', 'pt', [pageWidth, pageHeight]);

            // Add the image to the PDF document
            pdf.addImage(img, 'PNG', 0, 0, pageWidth, pageHeight);

            // Save the PDF document
            pdf.save('overview.pdf');
        }

        function exportToJPEG2() {
            var canvas = document.getElementById('chart-line2');
            var dataURL = canvas.toDataURL('image/jpeg');
            var link = document.createElement('a');
            link.href = dataURL;
            link.download = 'chart.jpeg';
            link.click();
        }



        // Iterate over the keys (dates) of the associative array

        // Assuming chart2 is an object with hour keys and registration counts as values


        // Iterate over each object in chart2 array


        // Ensure the elements exist before accessing their contexts
        var ctx1Element = document.getElementById("chart-line");
        var ctx2Element = document.getElementById("chart-line2");

        if (ctx1Element) {
            var ctx1 = ctx1Element.getContext("2d");
            new Chart(ctx1, {
                type: "bar",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Customers",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#5e72e4",
                        backgroundColor: gradientStroke1,
                        borderWidth: 3,
                        fill: true,
                        data: data,
                        maxBarThickness: 50
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: '#fbfbfb',
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                color: '#ccc',
                                padding: 20,
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            }
                        },
                    },
                },
            });
        } else {
            console.error("Element with ID 'chart-line' not found.");
        }

        if (ctx2Element) {
            var ctx2 = ctx2Element.getContext("2d");
            new Chart(ctx2, {
                type: "bar",
                data: {
                    labels: labels2,
                    datasets: [{
                        label: selectedDate,
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#5e72e4",
                        backgroundColor: gradientStroke2,
                        borderWidth: 3,
                        fill: true,
                        data: data2,
                        maxBarThickness: 70
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            enabled: false
                        },
                        datalabels: {
                            display: true,
                            color: 'black',
                            font: {
                                weight: 'bold'
                            },
                            anchor: 'end',
                            align: 'top',
                            formatter: function(value) {
                                return value;
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    scales: {
                        y: {
                            grid: {
                                drawBorder: false,
                                display: true,
                                drawOnChartArea: true,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                padding: 10,
                                color: '#fbfbfb',
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false,
                                display: false,
                                drawOnChartArea: false,
                                drawTicks: false,
                                borderDash: [5, 5]
                            },
                            ticks: {
                                display: true,
                                color: '#ccc',
                                padding: 20,
                                font: {
                                    size: 11,
                                    family: "Open Sans",
                                    style: 'normal',
                                    lineHeight: 2
                                },
                            }
                        },
                    },
                },
            });
        } else {
            console.error("Element with ID 'chart-line2' not found.");
        }
    </script>
    <script>
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

        // Function to initialize the countries chart
        function initializeCountriesChart(countries) {
            var countryData = countries.map(function(item) {
                return {
                    name: item.country || item.name || item.label || '',
                    y: item.count || 0
                };
            });

            Highcharts.chart('countriesChart', {
                chart: {
                    type: 'pie',
                    height: 400
                },
                title: {
                    text: 'Country Distribution',
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
                    data: countryData
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
            });
        }

        // Initialize the chart with the provided data
        initializeCountriesChart(@json($data['country']));
    </script>
@endsection
