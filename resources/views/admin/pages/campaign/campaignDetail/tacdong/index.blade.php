<div class="TacDong" id="tacDong">
    @include('admin.pages.campaign.campaignDetail.tacdong.tacdong')
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="title left">Tổng giá trị dự án</div>
            <div class="number">
                {{ number_format($campaign->totalMoney, 0, ',', '.') }} VND
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="title">Quỹ VIETNAM JOURNEY tài trợ</div>
            <div class="number">
                {{ number_format($campaign->moneyByVNJN, 0, ',', '.') }} VND
            </div>
        </div>
    </div>
</div>
