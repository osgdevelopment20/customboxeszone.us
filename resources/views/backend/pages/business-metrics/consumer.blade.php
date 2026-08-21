@extends('backend.layouts.app')
@section('title')
    Home
@endsection

@section('contents')
                <div class="row">
                    <div class="col">

                        <div class="h-100">
                            <div class="row mb-3 pb-1">
                                <div class="col-12">
                                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                        <div class="flex-grow-1">
                                            <h4 class="fs-16 mb-1">Hi, {{auth()->user()->name}}!</h4>
                                            <p class="text-muted mb-0">Here's what's happening with your Business today.</p>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-xl-4 col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Average Trip Cost</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">$<span class="counter-value" data-target="{{$avgTripCost}}">0</span>k </h4>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-soft-success rounded fs-3">
                                                            <i class="bx bx-dollar-circle text-success"></i>
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-6">
                                    <div class="card card-animate">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Average Trip Length</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-end justify-content-between mt-4">
                                                <div>
                                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{$avgTripLength}}">0</span> Days </h4>
                                                </div>
                                                <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-soft-success rounded fs-3">
                                                            <i class="bx bx-time text-success"></i>
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Traveler Spending</h4>
                                            <div class="row ">
                                                <div class="col-2 offset-10">
                                                    <select class="form-select form-select-sm   mb-3" id="filter" aria-label=".form-select-sm example" onchange="filterForAverageCostChart()">
                                                        <option value="90">Last 30 Days</option>
                                                        <option value="90">Last 90 Days</option>
                                                        <option value="365">Last 365 Days</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="lineChart" class="chartjs-chart" ></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Top 10 Destinations</h4>
                                            <div class="row ">
                                                <div class="col-2 offset-10">
                                                    <select class="form-select form-select-sm   mb-3" id="destinations-filter" aria-label=".form-select-sm example" onchange="filterForTopTenDestinations()">
                                                        <option value="30">Last 30 Days</option>
                                                        <option value="90">Last 90 Days</option>
                                                        <option value="365">Last 365 Days</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-striped" id="destination-table">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Destination Country</th>
                                                    <th>% of Sales</th>
                                                    <th>Average Trip Cost</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Traveler Share by Generation</h4>
                                            <div class="row ">
                                                <div class="col-2 offset-10">
                                                    <select class="form-select form-select-sm   mb-3" id="traveler-generation-filter" aria-label=".form-select-sm example" onchange="filterForTravelerGeneration()">
                                                        <option value="30">Last 30 Days</option>
                                                        <option value="90">Last 90 Days</option>
                                                        <option value="365">Last 365 Days</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-striped" id="traveler-generation-table">
                                                <thead>
                                                <tr>
                                                    <th>Generations</th>
                                                    <th>% of Sales</th>
                                                    <th>Average Trip Cost / Traveler</th>
                                                    <th>Total Trip Cost</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Domestic vs. International</h4>
                                            <div class="row ">
                                                <div class="col-2 offset-10">
                                                    <select class="form-select form-select-sm   mb-3" id="domestic-international-filter" aria-label=".form-select-sm example" onchange="domesticInternational()">
                                                        <option value="30">Last 30 Days</option>
                                                        <option value="90">Last 90 Days</option>
                                                        <option value="365">Last 365 Days</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-striped" id="domestic-international-table">
                                                <thead>
                                                <tr>
                                                    <th>Destination</th>
                                                    <th>% of Sales</th>
                                                    <th>Average Trip Cost</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Top-Selling Providers</h4>
                                            <div class="row ">
                                                <div class="col-2 offset-10">
                                                    <select class="form-select form-select-sm   mb-3" id="top-selling-providers-filter" aria-label=".form-select-sm example" onchange="TopSellingProviders()">
                                                        <option value="30">Last 30 Days</option>
                                                        <option value="90">Last 90 Days</option>
                                                        <option value="365">Last 365 Days</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-striped" id="top-selling-providers-table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Providers</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Top-Selling Policies</h4>
                                            <div class="row ">
                                                <div class="col-2 offset-10">
                                                    <select class="form-select form-select-sm   mb-3" id="top-selling-policies-filter" aria-label=".form-select-sm example" onchange="TopSellingPolicies()">
                                                        <option value="30">Last 30 Days</option>
                                                        <option value="90">Last 90 Days</option>
                                                        <option value="365">Last 365 Days</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-striped" id="top-selling-policies-table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Policies</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

        </div>


@endsection
@push('scripts')


    <!-- apexcharts -->
    <script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
    <!-- Chart JS -->
    <script src="{{asset('assets/libs/chart.js/chart.min.js')}}"></script>

    <!-- Vector map-->
    <script src="{{asset('assets/libs/jsvectormap/js/jsvectormap.min.js')}}"></script>
    <script src="{{asset('assets/libs/jsvectormap/maps/world-merc.js')}}"></script>

    <!--Swiper slider js-->
    <script src="{{asset('assets/libs/swiper/swiper-bundle.min.js')}}"></script>

    <!-- Dashboard init -->
    <script src="{{asset('assets/js/pages/dashboard-ecommerce.init.js')}}"></script>

    <!-- App js -->
    <script src="{{asset('assets/js/app.js')}}"></script>
    <script>
        $(document).ready(function() {
            filterForAverageCostChart()
            filterForTopTenDestinations()
            filterForTravelerGeneration()
            domesticInternational()
            TopSellingProviders()
            TopSellingPolicies()
        });
        function filterForTopTenDestinations() {
            var destinationsFilterDate = $('#destinations-filter').val();

            $.ajax({
                url: '{{url('/api/v1/top-ten-destinations-data')}}',
                method: 'GET',
                data: {start_date: destinationsFilterDate},
                success: function (data) {

                    console.log(data.data)
                    // Clear existing table data
                    $('#destination-table tbody').empty();

                    // Iterate over the data and append rows to the table
                    $.each(data.data, function (index, item) {
                        console.log(item)
                        var row = `<tr>
                                <th scope="row">${index + 1}</th>
                                <td>${item.country_name}</td>
                                <td>${item.percentage} %</td>
                                <td>$ ${item.average_cost}</td>
                            </tr>`;
                        $('#destination-table tbody').append(row);
                    });
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
        function filterForTravelerGeneration() {
            var travelerGenerationFilterDate = $('#traveler-generation-filter').val();

            $.ajax({
                url: '{{url('/api/v1/traveler-generation-data')}}',
                method: 'GET',
                data: {start_date: travelerGenerationFilterDate},
                success: function (data) {
                    // Clear existing table data
                   $('#traveler-generation-table tbody').empty();

                    // Iterate over the data and append rows to the table
                    $.each(data.data, function (index, item) {
                        console.log(item,index)
                        var row = `<tr>

                                <td>${index}</td>
                                <td>${item.percentage} %</td>
                                <td>$ ${item.average_cost}</td>
                                <td>$ ${item.total_trip_cost} </td>
                            </tr>`;
                        $('#traveler-generation-table tbody').append(row);
                    });
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
        function domesticInternational() {

            var domesticInternationalFilterDate = $('#domestic-international-filter').val();

            $.ajax({
                url: '{{url('/api/v1/domestic-international-data')}}',
                method: 'GET',
                data: {start_date: domesticInternationalFilterDate},
                success: function (data) {
                    // Clear existing table data
                   $('#domestic-international-table tbody').empty();

                    // Iterate over the data and append rows to the table
                    $.each(data.data, function (index, item) {
                        console.log(item,index)
                        var row = `<tr>
                                <td>${index}</td>
                                <td>${item.percentage} %</td>
                                <td>$ ${item.average_cost}</td>
                            </tr>`;
                        $('#domestic-international-table tbody').append(row);
                    });
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
        function TopSellingProviders() {

            var topSellingProvidersFilterDate = $('#top-selling-providers-filter').val();

            $.ajax({
                url: '{{url('/api/v1/top-selling-providers-data')}}',
                method: 'GET',
                data: {start_date: topSellingProvidersFilterDate},
                success: function (data) {
                    // Clear existing table data
                   $('#top-selling-providers-table tbody').empty();

                    // Iterate over the data and append rows to the table
                    $.each(data.data, function (index, item) {
                        console.log(item,index)
                        var row = `<tr>
                                <td>${index + 1}</td>
                                <td>${item.name}</td>
                            </tr>`;
                        $('#top-selling-providers-table tbody').append(row);
                    });
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        function TopSellingPolicies() {

            var topSellingPoliciesFilterDate = $('#top-selling-policies-filter').val();

            $.ajax({
                url: '{{url('/api/v1/top-selling-policies-data')}}',
                method: 'GET',
                data: {start_date: topSellingPoliciesFilterDate},
                success: function (data) {
                    // Clear existing table data
                   $('#top-selling-policies-table tbody').empty();

                    // Iterate over the data and append rows to the table
                    $.each(data.data, function (index, item) {
                        console.log(item,index)
                        var row = `<tr>
                                <td>${index + 1}</td>
                                <td>${item.name}</td>
                            </tr>`;
                        $('#top-selling-policies-table tbody').append(row);
                    });
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }

        var lineChart;
        function filterForAverageCostChart() {
            // document.getElementById("lineChart").destroy();
            var selectedValue = $('#filter').val();
            // alert(selectedValue)
            $.ajax({
                url: '{{url('/api/v1/average-cost-chart-data')}}',
                method: 'GET',
                data: {start_data: selectedValue},
                success: function (data) {
                    destroyAverageCostLineChart();
                    console.log(data)
                    initiateAverageCostLineChart(data.data)
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        }


        function initiateAverageCostLineChart(data)
        {
        var  islinechart = document.getElementById("lineChart");
            lineChart = new Chart(islinechart, {
                type: "line",
                data: {
                    labels: data.date,
                    datasets: [{
                        label: "Average Trip Cost",
                        fill: !0,
                        lineTension: .5,
                        backgroundColor: 'rgba(123, 141, 152, 0.1)',
                        borderColor: 'rgb(75, 192, 192)',
                        borderCapStyle: "butt",
                        borderDash: [],
                        borderDashOffset: 0,
                        borderJoinStyle: "miter",
                        pointBorderColor: 'rgb(75, 192, 192)',
                        pointBackgroundColor: "#fff",
                        pointBorderWidth: 1,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: 'rgb(75, 192, 192)',
                        pointHoverBorderColor: "#fff",
                        pointHoverBorderWidth: 2,
                        pointRadius: 1,
                        pointHitRadius: 10,
                        data: data.average_cost
                    }]
                },
                options: {
                    x: {ticks: {font: {family: "Poppins"}}},
                    y: {ticks: {font: {family: "Poppins"}}},
                    plugins: {legend: {labels: {font: {family: "Poppins"}}}}
                }
            })
        }
        function destroyAverageCostLineChart() {
            if (lineChart) {
                lineChart.destroy();
            }
        }
    </script>
@endpush

