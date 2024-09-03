<div class="ChiTiet">
    @include('admin.pages.campaign.campaignDetail.chitiet.chitiet')
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 left">
            <hr/>
            <div class="title">Thời gian dự án</div>
            <div class="time">
                <div class="inner-title">Giai đoạn ban đầu</div>
                <div class="desc">{{ $campaign->timeline[0]['value'] }}</div>
                <div class="inner-title">Bắt đầu dự án</div>
                <div class="desc">{{ $campaign->timeline[1]['value'] }}</div>
                <div class="inner-title">Kết thúc dự án</div>
                <div class="desc">{{ $campaign->timeline[2]['value'] }}</div>
                <div class="inner-title">Tổng kết dự án</div>
                <div class="desc">{{ $campaign->timeline[3]['value'] }}</div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 right">
            <hr/>
            <div class="title">Đăng ký tham gia</div>
            <div class="register">
                <div class="desc">Số lượng TNV tham gia: 24 TNV</div>
            </div>
            <hr/>
            <div class="title">Địa điểm cụ thể</div>
            @foreach ($campaign->location as $lo)
                <div class="location-desc">{{ $lo['name'] }}</div>
            @endforeach
        </div>
    </div>

    <div class="plan-row">
        <hr />
        <div class="title">Kế hoạch chiến dịch</div>
        <pre class="desc">{!! $campaign->plan !!}</pre>
    </div>
</div>

