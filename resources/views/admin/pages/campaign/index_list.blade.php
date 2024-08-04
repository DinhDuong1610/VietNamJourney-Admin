@extends('admin.layouts.main')

@section('title', 'Campaign')
@section('page-title', 'Chiến dịch')

@section('card-title')
    <div class="w-100 d-flex justify-content-between align-items-center">
        <span style="font-size: 24px; font-weight: 600; line-height: 100%;">CHIẾN DỊCH</span>
    </div>
@endsection

@section('overview-content')
    <div class="row mt-3">
        <!-- /.col -->
        <div class="col-4">
            <div class="info-box shadow">
                <span class="info-box-icon bg-success">
                    <i class="far fa-flag"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Chiến dịch đang diễn ra</span>
                    <span class="info-box-number">{{ $statistics['campaignIng'] }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-4">
            <div class="info-box shadow">
                <span class="info-box-icon bg-warning">
                    {{-- icon flag --}}
                    <i class="far fa-flag"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Chiến dịch sắp tới</span>
                    <span class="info-box-number">{{ $statistics['campaignWill'] }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-4">
            <div class="info-box shadow">
                <span class="info-box-icon bg-danger">
                    {{-- icon start progress --}}
                    <i class="far fa-flag"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Chiến dịch đã kết thúc</span>
                    <span class="info-box-number">{{ $statistics['campaignEd'] }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@section('card-header')
    <div class="card-header d-flex justify-content-between align-items-center mr-0 pr-0">
        <div>Danh sách chiến dịch</div>
        <div class="d-flex flex-row-reverse flex-grow-1">
            <input id="campaignIdInput" class="form-control w-25 mr-2" type="search" placeholder="Mã chiến dịch"
                aria-label="Search">
            <select class="form-control w-25 mr-2" id="provinceSelect">
                <option value="Tất cả" selected>Tất cả</option>
            </select>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    fetch('/json_data_vn_units.json')
                        .then(response => response.json())
                        .then(provinces => {
                            const provinceSelect = document.getElementById('provinceSelect');

                            provinces.forEach(province => {
                                const option = document.createElement('option');
                                option.value = province.Name;
                                option.textContent = province.Name;

                                // Lấy giá trị province từ query parameters
                                const urlParams = new URLSearchParams(window.location.search);
                                const selectedProvince = urlParams.get('province') || 'Tất cả';

                                // Kiểm tra nếu giá trị của option khớp với giá trị đã chọn
                                if (province.Name === selectedProvince) {
                                    option.selected = true;
                                }
                                provinceSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error loading provinces:', error));
                });


                document.getElementById('provinceSelect').addEventListener('change', function() {
                    selectedProvince = this.value;
                    let url;

                    if (selectedProvince === 'Tất cả') {
                        url = `{{ route('admin.pages.campaign.list') }}`;
                    } else {
                        url =
                            `{{ route('admin.pages.campaign.list.searchByProvince') }}?province=${encodeURIComponent(selectedProvince)}`;
                    }
                    window.location.href = url;
                });

                document.getElementById('campaignIdInput').addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        let campaignId = this.value.trim();

                        if (campaignId.startsWith('fp') || campaignId.startsWith('FP') || campaignId.startsWith('Fp')) {
                            campaignId = campaignId.substring(2);
                        }

                        if (campaignId) {
                            const url =
                                `{{ route('admin.pages.campaign.list.searchById') }}?campaignId=${encodeURIComponent(campaignId)}`;
                            window.location.href = url;
                        }
                    }
                });
            </script>
        </div>
    </div>
@endsection

@section('card-table-content')
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="text-center" style="width: 50px">ID</th>
                <th style="min-width: 300px">Tên chiến dịch</th>
                <th style="width: 200px">Tỉnh/Thành phố</th>
                {{-- <th style="min-width: 250px">Người tạo</th> --}}
                <th class="text-center" style="width: 200px">Ngày bắt đầu</th>
                <th class="text-center" style="width: 200px">Ngày kết thúc</th>
                <th class="text-center" style="width: 150px">Tham gia</th>
                <th class="text-center" style="width: 150px">Chờ duyệt</th>
                <th class="text-center" style="width: 150px">Trạng thái</th>
                <th class="text-center" style="width: 50px"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($campaigns as $campaign)
                <tr data-campaign-id="{{ $campaign->id }}" style="cursor: pointer;">
                    <td class="text-center">FP{{ $campaign->id }}</td>
                    <td>{{ $campaign->name }}</td>
                    <td>{{ $campaign->province }}</td>
                    <td class="text-center">{{ $campaign->dateStart }}</td>
                    <td class="text-center">{{ $campaign->dateEnd }}</td>
                    <td class="text-center">{{ $campaign->joined }}</td>
                    <td class="text-center">{{ $campaign->pending }}</td>
                    <td class="text-center">
                        @if ($campaign->status == 'ongoing')
                            <span class="badge badge-pill badge-success w-100"><span class="d-inline-block py-2">Đang diễn
                                    ra</span></span>
                        @elseif ($campaign->status == 'upcoming')
                            <span class="badge badge-pill badge-warning w-100"><span class="d-inline-block py-2">Sắp diễn
                                    ra</span></span>
                        @elseif ($campaign->status == 'ended')
                            <span class="badge badge-pill badge-danger w-100"><span class="d-inline-block py-2">Đã kết
                                    thúc</span></span>
                        @else
                            <span class="badge badge-pill badge-secondary w-100">Không xác định</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
    </table>

    <!-- Hiển thị các liên kết phân trang -->
    <div class="d-flex justify-content-center mt-3">
        {{-- {{ $campaigns->links('pagination::bootstrap-4') }} --}}
        @if ($campaigns instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {!! $campaigns->appends(['province' => request()->has('province') ? request('province') : ''])->links('pagination::bootstrap-4') !!}
        @endif
    </div>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">


    <!-- Thêm CSS tùy chỉnh -->
    <style>
        .pagination .page-link {
            color: #28a745;
            /* Màu xanh lá cho văn bản */
        }

        .pagination .page-item.active .page-link {
            background-color: #28a745;
            /* Màu nền xanh lá cho trang hiện tại */
            border-color: #28a745;
            /* Viền màu xanh lá cho trang hiện tại */
        }

        .pagination .page-link:hover {
            color: #218838;
            /* Màu xanh lá đậm hơn khi hover */
        }

        .pagination .page-link:focus {
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            /* Bóng xanh lá khi focus */
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#example1 tbody tr').forEach(function(row) {
                row.addEventListener('click', function() {
                    var campaignId = this.getAttribute('data-campaign-id');
                    var url =
                        `{{ route('admin.pages.campaign.detail', ['campaignId' => '__campaignId__']) }}`
                        .replace('__campaignId__',
                            campaignId);
                    window.location.href = url;
                });
            });
        });
    </script>
@endsection

@section('card-body')
    @include('admin.pages.campaign.campaignList')
@endsection

@section('card-footer')

@endsection

@section('content')
    {{-- @include('admin.partials.card') --}}
    @include('admin.pages.campaign.campaignList')
@endsection
