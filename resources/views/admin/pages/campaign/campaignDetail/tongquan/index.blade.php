<div class="TongQuan">
    @include('admin.pages.campaign.campaignDetail.tongquan.tongquan')
    <div class="left">
        <hr />
        <div class="inner">
            <p class="title">TÌNH TRẠNG</p>
            <p class="time">{{ $statusText }}</p>
        </div>
        <div class="inner">
            <p class="title">NGÀY BẮT ĐẦU</p>
            <p class="time">{{ $campaign->dateStart }}</p>
        </div>
        <div class="inner">
            <p class="title">NGÀY KẾT THÚC</p>
            <p class="time">{{ $campaign->dateEnd }}</p>
        </div>
        <div class="inner">
            <p class="title">ĐỊA ĐIỂM</p>
            <p class="time">{{ $campaign->district }}</p>
        </div>
    </div>
    <div class="right">
        <pre class="description">
        <div>{!! $campaign->description !!}</div>
      </pre>
    </div>
</div>

