<div class="ChiTiet">
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
            <pre class="desc">{{ $campaign->location }}</pre>
        </div>
    </div>

    <div class="plan-row">
        <hr />
        <div class="title">Kế hoạch chiến dịch</div>
        <pre class="desc">{!! $campaign->plan !!}</pre>
    </div>
</div>

<style>
    .ChiTiet {
        width: 100%;
        margin-top: 40px;
        padding-top: 20px;
        background-color: white;
        padding-bottom: 10px
    }

    .ChiTiet hr {
        width: 150px;
        border: 2px solid #35973F;
        opacity: 1;
        margin-bottom: 10px;
        margin-top: 40px;
        display: inline-block;
    }

    .ChiTiet pre {
        white-space: pre-wrap; // Giữ nguyên ngắt dòng và cho phép ngắt dòng tự động
        word-wrap: break-word; // Tự động ngắt dòng khi từ quá dài
    }

    .ChiTiet .left {
        padding-left: 50px;
    }

    .ChiTiet .left .time {
        margin-left: 10px;
        border-left: 3px solid #001273;
        padding-left: 40px;
    }

    .ChiTiet .left .inner-title {
        font-size: 28px;
        font-weight: 700;
        color: #001273;
        margin-bottom: 5px;
    }

    .ChiTiet .left .desc {
        font-size: 24px;
        font-weight: 600;
        color: #737373;
        margin-bottom: 30px;
    }

    .ChiTiet .title {
        font-size: 30px;
        font-weight: 600;
        color: #35973F;
        margin-bottom: 20px;
    }

    .ChiTiet .right .register {
        display: flex;
        justify-content: space-between;
        padding-right: 30px;
        align-items: center;
        margin-bottom: 10px;
    }

    .ChiTiet .right .register .button {
        padding: 5px 20px;
        border: none;
        border-radius: 8px;
        font-size: 24px;
        font-weight: 600;
        color: white;
        background-color: #1A931F;
    }

    .ChiTiet .right .desc {
        font-size: 28px;
        font-weight: 600;
        color: #4F4F4F;
        font-family: 'montserrat', sans-serif;
        line-height: 200%;
    }

    .ChiTiet .plan-row {
        padding-left: 50px;
        padding-right: 50px;
    }

    .ChiTiet .plan-row .desc {
        color: black;
        font-size: 22px;
        font-weight: 500;
        text-align: justify;
        font-family: 'montserrat', sans-serif;
    }
</style>
