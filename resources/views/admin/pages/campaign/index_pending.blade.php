@extends('admin.layouts.main')

@section('title', 'Chiến dịch')
@section('page-title', 'Chiến dịch')

@section('card-title')
    <div class="d-flex justify-content-between align-items-center mr-0 pr-0">
        <div>Danh sách chiến dịch</div>
        <div class="d-flex flex-row-reverse flex-grow-1">
            <input id="campaignIdInput" class="form-control w-25 mr-2" type="search" placeholder="Mã chiến dịch"
                aria-label="Search">
            <select class="form-control w-25 mr-2" id="provinceSelect">
                <option value="Tất cả" selected>Tất cả</option>
            </select>
        </div>
    </div>
@endsection

@section('overview-content')

@endsection

@section('css-table')

@endsection

@section('card-table-content')
    <table id="example1" class="table-campaign-pending table table-bordered table-striped">
        <thead>
            <tr>
                <th class="text-center" style="width: 50px">ID</th>
                <th style="min-width: 300px">Tên chiến dịch</th>
                <th style="width: 200px">Tỉnh/Thành phố</th>
                <th class="text-center" style="width: 150px">Ngày bắt đầu</th>
                <th class="text-center" style="width: 150px">Ngày kết thúc</th>
                <th style="width: 300px">Người tạo</th>
                <th class="text-center" style="width: 120px"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($campaigns as $campaign)
                <tr id="campaign-row-{{ $campaign->id }}" data-campaign-id="{{ $campaign->id }}" style="cursor: pointer;">
                    <td class="text-center">FP{{ $campaign->id }}</td>
                    <td>{{ $campaign->name }}</td>
                    <td>{{ $campaign->province }}</td>
                    <td class="text-center">{{ $campaign->dateStart }}</td>
                    <td class="text-center">{{ $campaign->dateEnd }}</td>
                    <td class="name">
                        <img class="avatar" src="{{ asset($campaign->user->userInformation->Image) }}" alt="avatar"
                            class="img-circle img-size-32 mr-2">
                        <div class="inner-name">
                            <div class="fullname">{{ $campaign->user->userInformation->Name }}</div>
                            <div class="username">{{ $campaign->user->userInformation->Username }}</div>
                        </div>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-success" onclick="updateStatus({{ $campaign->id }})">
                            Duyệt <i class="fas fa-check"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            @yield('css-table')
    </table>

    <!-- Panigation -->
    <div class="d-flex justify-content-center mt-3">
        @if ($campaigns instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {!! $campaigns->appends(['province' => request()->has('province') ? request('province') : ''])->links('pagination::bootstrap-4') !!}
        @endif
    </div>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('card-body')
    @include('admin.pages.campaign.campaignPending')
@endsection

@section('card-footer')

@endsection

@section('content')
    @include('admin.partials.card')
@endsection
