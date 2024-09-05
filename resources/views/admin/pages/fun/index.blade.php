@extends('admin.layouts.main')

@section('title', 'Quỹ')
@section('page-title', 'Quỹ')

@section('card-title')
    <div class="w-100 d-flex justify-content-between align-items-center ">
        <div>Quỹ</div>
    </div>
@endsection

@section('card-body')
    @include('admin.pages.fun.fun')
    <div class="row mt-3">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($funOfVNJN, 0, ',', '.') }} VND</h3>
                    <p>Tổng số tiền quỹ nhận được</p>
                </div>
                <div class="icon">
                    <i class="nav-icon fas fa-money-bill-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($funCurrent, 0, ',', '.') }} VND</h3>
                    <p>Tổng số tiền quỹ hiện có</p>
                </div>
                <div class="icon">
                    <i class="nav-icon fas fa-money-bill-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($funOfCampaign, 0, ',', '.') }} VND</h3>
                    <p>Tổng số tiền quyên góp cho chiến dịch</p>
                </div>
                <div class="icon">
                    <i class="nav-icon fas fa-money-bill-alt"></i>
                </div>
            </div>
        </div>
    </div>


    <!-- BAR CHART -->
    <div class="row">
        <div class="col-8">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Tổng số tiền quỹ Việt Nam Journey nhận được</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="barChart-funOfVNJN"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Quỹ Việt Nam Journey và quỹ chiến dịch</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="donutChart-fun"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>



    <table id="example1" class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th class="text-center" style="width: 50px">ID</th>
                <th style="min-width: 300px">Tên chiến dịch</th>
                <th style="width: 200px">Tỉnh/Thành phố</th>
                <th class="text-center" style="width: 200px">Quỹ dự kiến</th>
                <th class="text-center" style="width: 200px">Quỹ quyên góp</th>
                <th class="text-center" style="width: 150px">Tình trạng quỹ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($funCampaigns as $campaign)
                <tr data-campaign-id="{{ $campaign->id }}" style="cursor: pointer;">
                    <td class="text-center">FP{{ $campaign->id }}</td>
                    <td>{{ $campaign->name }}</td>
                    <td>{{ $campaign->province }}</td>
                    <td class="text-center text-bold">{{ number_format($campaign->totalMoney ?? 0, 0, ',', '.') }}đ</td>
                    <td class="text-center text-bold">{{ number_format($campaign->amount ?? 0, 0, ',', '.')}}đ</td>
                    <td class="text-center">
                        @if ($campaign->status == 'upcoming' && $campaign->amount < $campaign->totalMoney)
                            <span class="badge badge-pill badge-success w-100"><span class="d-inline-block py-2">Đang diễn
                                    ra</span></span>
                        @else
                            <span class="badge badge-pill badge-danger w-100"><span class="d-inline-block py-2">Đã kết
                                    thúc</span></span>
                        @endif
                    </td>
                </tr>
            @endforeach
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        @if ($funCampaigns instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {!! $funCampaigns->appends(['province' => request()->has('province') ? request('province') : ''])->links('pagination::bootstrap-4') !!}
        @endif
    </div>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

@endsection

@section('content')
    @include('admin.partials.card')
@endsection


@section('js')
    <script src="{{ asset('admin-rs/plugins/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function() {
            var funStatistics = @json($funStatistics);

            var areaChartData = {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8',
                    'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
                ],
                datasets: [{
                        label: 'Số tiền quỹ nhận được',
                        backgroundColor: '#28A745',
                        borderColor: '#28A745',
                        pointRadius: false,
                        pointColor: '#28A745',
                        pointStrokeColor: '#28A745',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: '#28A745',
                        data: funStatistics.map(stat => stat.amount)
                    },

                ]
            }


            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $('#barChart-funOfVNJN').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            var temp0 = areaChartData.datasets[0]
            barChartData.datasets[0] = temp0

            var barChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                datasetFill: false
            }

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            })
        })


        //-------------
        //- DONUT CHART -
        //-------------
        var donutChartCanvas = $('#donutChart-fun').get(0).getContext('2d')
        var donutData = {
            labels: [
                'Quỹ Việt Nam Journey',
                'Quỹ chiến dịch',
            ],
            datasets: [{
                data: [{{ $funOfVNJN }}, {{ $funOfCampaign }}],
                backgroundColor: ['#00a65a', '#f39c12'],
            }]
        }
        var donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        new Chart(donutChartCanvas, {
            type: 'doughnut',
            data: donutData,
            options: donutOptions
        })
    </script>

@endsection
