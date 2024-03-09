@extends('layout')
@section('title', '- Car')
@section('menu')
    @include('components.navigation')
@endsection
@section('content')

    <main class="user-dashboard">
        <h1>{{ $user->first_name }}'s Car Dashboard</h1>
        <div class="container">
            <div class="first-container">
                <div class="data-point-title">Earnings</div>
                <div class="data-points">
                    <div class="data-card">
                        <div class="heading">Total Earned</div>
                        <div class="data">${{ $totalEarned }}</div>
                    </div>
                    <div class="data-card">
                        <div class="heading">Best Performing Car</div>
                        @if ($bestPerformer == null)
                            You don't have any cars yet, or your cars haven't accumulated any bookings.
                        @else
                            <a href="/dashboard/my-rentals/{{ $bestPerformer->id }}">
                                <div class="car">{{ $bestPerformer->brand }} {{ $bestPerformer->model }} :</div>
                            </a>
                            <div class="data">
                                ${{ $bestPerformer->total_cost_of_bookings }}</div>
                        @endif

                    </div>
                </div>
            </div>

            <div class="second-container">
                <div class="requests-header">Total Requests</div>
                <div class="requests">
                    <div class="data-card request accepted ">
                        <div class="heading">Accepted</div>
                        <div class="data"> {{ $accepted }}</div>

                    </div>
                    <div class="data-card request declined">
                        <div class="heading">Declined</div>
                        <div class="data"> {{ $declined }}</div>
                    </div>
                    <div class="data-card request pending">
                        <div class="heading">Pending</div>
                        <div class="data"> {{ $pending }}</div>
                    </div>
                </div>
            </div>



            <div class="chart-container">
                <div class="chart-header">
                    All cars
                </div>
                @if ($totalBookings == 0)
                    <div class="no-data">There is no bookings this month for any of your cars.</div>
                @else
                    <canvas id="myChart"></canvas>
                @endif
                <div class="chart-footer" id="chart">
                    <a href="/userDashboard?date={{ date('Y-m', strtotime('-1 month', strtotime($date . '-01'))) }}#chart">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                    <div class="month"> {{ date('M', strtotime($date)) . ' ' . date('Y', strtotime($date)) }}</div>
                    <a href="/userDashboard?date={{ date('Y-m', strtotime('+1 month', strtotime($date . '-01'))) }}#chart">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Check if the chart div exists
        const chartDiv = document.getElementById('myChart');

        if (chartDiv) {
            const ctx = chartDiv.getContext('2d');

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($carPerformances['labels']),
                    datasets: [{
                        label: 'Number of Requests',
                        data: @json($carPerformances['carCount']),
                        borderWidth: 1,
                        backgroundColor: "#4e71ba",
                        borderColor: '#79a2d8',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'All bookings for your cars by month.'
                        },
                    },
                    interaction: {
                        intersect: false,
                    },
                    scales: {
                        y: {
                            beginAtZero: true,

                        },
                        x: {
                            ticks: {
                                display: true,
                                maxRotation: 0,
                                minRotation: 0
                            }
                        }


                    },
                    scale: {
                        ticks: {
                            precision: 0
                        }
                    }
                }
            });

            function updateChart() {
                if (window.innerWidth <= 600) {
                    chart.options.scales.x.ticks.maxRotation = 90;
                    chart.options.scales.x.ticks.minRotation = 90;
                } else {
                    chart.options.scales.x.ticks.maxRotation = 0;
                    chart.options.scales.x.ticks.minRotation = 0;
                }
                chart.update();
            }
            updateChart();

            window.addEventListener('resize', updateChart);
        }
    </script>

@endsection
