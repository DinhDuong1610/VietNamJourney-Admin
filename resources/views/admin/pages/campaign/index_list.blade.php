@extends('admin.layouts.main')

@section('title', 'Chiến dịch')
@section('page-title', 'Chiến dịch')

@section('card-title')
    <div class="w-100 d-flex justify-content-between align-items-center">
        <span style="font-size: 24px; font-weight: 600; line-height: 100%;">CHIẾN DỊCH</span>
    </div>
@endsection

@section('overview-content')
    <div class="row mt-3">
        <div class="col-4">
            <div class="info-box shadow">
                <span class="info-box-icon bg-success">
                    <i class="far fa-flag"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Chiến dịch đang diễn ra</span>
                    <span class="info-box-number">{{ $statistics['campaignIng'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box shadow">
                <span class="info-box-icon bg-warning">
                    <i class="far fa-flag"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Chiến dịch sắp tới</span>
                    <span class="info-box-number">{{ $statistics['campaignWill'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="info-box shadow">
                <span class="info-box-icon bg-danger">
                    <i class="far fa-flag"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">Chiến dịch đã kết thúc</span>
                    <span class="info-box-number">{{ $statistics['campaignEd'] }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('card-header')
    <div class="card-header d-flex justify-content-between align-items-center mr-0 pr-0">
        <div>Danh sách chiến dịch</div>
        <div class="d-flex flex-row-reverse flex-grow-1">
            <input id="campaignIdInput" class="form-control w-25 mr-2 custom-success-input" type="search" placeholder="Mã chiến dịch"
                aria-label="Search">
            <select class="form-control w-25 mr-2 custom-success-select" id="provinceSelect">
                <option value="Tất cả" selected>Tất cả</option>
            </select>
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

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        @if ($campaigns instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {!! $campaigns->appends(['province' => request()->has('province') ? request('province') : ''])->links('pagination::bootstrap-4') !!}
        @endif
    </div>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

@endsection

@section('card-body')
    @include('admin.pages.campaign.campaignList')
@endsection

@section('card-footer')

@endsection

@section('content')
    @include('admin.pages.campaign.campaignList')
@endsection
