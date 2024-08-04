<div class="TacDong" id="tacDong">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="title left">Tổng giá trị dự án</div>
            <div class="number">
                <!-- Hiển thị giá trị tổng tiền dự án -->
                {{ number_format($campaign->totalMoney, 0, ',', '.') }} VND
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
            <div class="title">Quỹ VIETNAM JOURNEY tài trợ</div>
            <div class="number">
                <!-- Hiển thị giá trị quỹ tài trợ -->
                {{ number_format($campaign->moneyByVNJN, 0, ',', '.') }} VND
            </div>
        </div>
    </div>
</div>

<style>
    .TacDong {
        padding-top: 0px;
        /* padding-left: 100px;
        padding-right: 100px; */
    }

    .TacDong pre {
        white-space: pre-wrap; // Giữ nguyên ngắt dòng và cho phép ngắt dòng tự động
        word-wrap: break-word; // Tự động ngắt dòng khi từ quá dài
    }

    .TacDong .title {
        font-size: 32px;
        font-weight: 600;
        color: #6A6A6A;
        margin-bottom: 40px;
        padding-top: 20px;
        border-top: 4px solid #35973F;
    }

    .TacDong .left {
        margin-right: 100px;
    }

    .TacDong .number {
        font-size: 85px;
        font-weight: 600;
        color: #35973F;
        line-height: 100%;
        margin-bottom: 40px;
    }

    .TacDong .desc {
        font-size: 60px;
        font-weight: 600;
        color: black;
        line-height: 100%;
    }
</style>
