@extends('admin.layouts.main')

@section('title', 'Quản lý')
@section('page-title', 'Welcome')

@section('card-title', 'Danh sách người dùng')

@section('table-user')
    @php
        $currentPageUser = $users->currentPage();
        $perPageUser = $users->perPage();
        $startUser = ($currentPageUser - 1) * $perPageUser + 1;
    @endphp
    <table class="table table-bordered table-users">
        <thead>
            <tr>
                <th class="text-center" style="width: 10px"></th>
                <th style="min-width: 270px">Tên</th>
                <th class="text-center" style="width: 150px">Chiến dịch</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
                <tr>
                    <td class="text-center">{{ $startUser + $index }}.</td>
                    <td class="name">
                        <img class="avatar" src="{{ asset($user->userInformation->Image) }}" alt="avatar"
                            class="img-circle img-size-32 mr-2">
                        <div class="inner-name">
                            <div class="fullname">{{ $user->userInformation->Name ?? 'N/A' }}</div>
                            <div class="username">{{ $user->userInformation->Username ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td class="text-center">{{ $user->joined }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-3 paginate-dashboard-user">
        {{ $users->appends(['tab' => 'user'])->links('pagination::bootstrap-4') }}
    </div>


@endsection

@section('table-quy')
    @php
        $currentPageQuy = $donationAndCampaignsByProvince->currentPage();
        $perPageQuy = $donationAndCampaignsByProvince->perPage();
        $startQuy = ($currentPageQuy - 1) * $perPageQuy + 1;
    @endphp
    <table class="table table-bordered table-dashboard-fun">
        <thead>
            <tr>
                <th class="text-center" style="width: 10px"></th>
                <th style="min-width: 200px">Tỉnh Thành</th>
                <th class="text-center" style="width: 150px">Chiến Dịch</th>
                <th class="text-center" style="width: 300px">Quỹ hỗ trợ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($donationAndCampaignsByProvince as $index => $data)
                <tr>
                    <td class="text-center">{{ $startQuy + $index }}.</td>
                    <td>{{ $data->province }}</td>
                    <td class="text-center">{{ $data->total_campaigns }}</td>
                    <td class="text-center">{{ number_format($data->total_donation, 0, ',', '.') }} VND</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-3 mb-3 paginate-dashboard-quy">
        {{ $donationAndCampaignsByProvince->appends(['tab' => 'quy'])->links('pagination::bootstrap-4') }}
    </div>



@endsection

@section('overview-content')
    <div class="row mt-3">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ count($userss) }}</h3>
                    <p>Người dùng</p>
                </div>
                <div class="icon">
                    <i class="nav-icon fas fa-user"></i>
                </div>
                <a href="#" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ count($campaigns) }}</h3>
                    <p>Chiến dịch</p>
                </div>
                <div class="icon">
                    <i class="nav-icon fas fa-flag"></i>
                </div>
                <a href="#" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ count($posts) }}</h3>
                    <p>Bài viết</p>
                </div>
                <div class="icon">
                    <i class="right fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($totalMoney, 0, ',', '.') }} VND</h3>
                    <p>Quỹ Việt Nam Journey</p>
                </div>
                <div class="icon">
                    <i class="nav-icon fas fa-money-bill-alt"></i>
                </div>
                <a href="#" class="small-box-footer">Xem thêm <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
@endsection

@section('card-body')
    @include('admin.pages.dashboard.dashboard')
@endsection

@section('card-footer')

@endsection

@section('content')
    @include('admin.pages.dashboard.dashboard')
@endsection

@section('js')
    <script src="{{ asset('admin-rs/plugins/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function() {
            var campaignStatistics = @json($campaignStatistics);

            var areaChartData = {
                labels: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8',
                    'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
                ],
                datasets: [{
                        label: 'Số lượng chiến dịch diễn ra',
                        backgroundColor: '#28A745',
                        borderColor: '#28A745',
                        pointRadius: false,
                        pointColor: '#28A745',
                        pointStrokeColor: '#28A745',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: '#28A745',
                        data: campaignStatistics.map(stat => stat.count)
                    },

                ]
            }


            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $('#barChart').get(0).getContext('2d')
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
        var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
        var donutData = {
            labels: [
                'Người dùng tham gia tạo chiến dịch',
                'Người dùng đã tham gia chiến dịch',
                'Người dùng chưa tham gia chiến dịch',
            ],
            datasets: [{
                data: [{{ $userProfessional }}, {{ $userJoined }}, {{ $userNotJoined }}],
                backgroundColor: ['#f56954', '#00a65a', '#f39c12'],
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

        //-------------
        //- PIE CHART -
        //-------------
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieData = donutData;
        var pieOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        })
    </script>

@endsection
