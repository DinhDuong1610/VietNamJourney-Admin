<div class="ThongTinThem">
    <div class="contact">
        <hr />
        <div class="title">Liên hệ</div>
        <div class="info">
            @foreach ($campaign->infoContact as $contact)
                <div class="item">
                    <p class="title">{{ $contact['organizationName'] }}</p>
                    <p class="email">
                        <i class="nav-icon fas fa-envelope"></i> {{ $contact['contactEmail'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="organizations">
        <hr />
        <div class="title">Các tổ chức hỗ trợ thực hiện</div>
        <div class="info">
            @foreach ($campaign->infoOrganization as $contact)
                <div class="item">
                    <p class="title">{{ $contact['organizationName'] }}</p>
                    <p class="email">     
                        <i class="nav-icon fas fa-envelope"></i> {{ $contact['contactEmail'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .ThongTinThem {
        width: 100%;
        margin-top: 0px;
        background-color: white;
        padding-top: 30px;
        padding-bottom: 50px;
        padding-left: 50px;
        display: flex;
        flex-wrap: wrap;

        
    }

    .ThongTinThem hr {
            width: 150px;
            border: 2px solid #35973F;
            opacity: 1;
            margin-bottom: 10px;
            margin-top: 0px;
            display: inline-block;
        }

    .ThongTinThem .contact {
        width: 50%;
    }

    .ThongTinThem .organizations {
        width: 50%;
    }

    .ThongTinThem .title {
        font-size: 30px;
        font-weight: 600;
        color: #35973F;
        margin-bottom: 20px;
    }

    .ThongTinThem .item {
        margin-bottom: 30px;
    }

    .ThongTinThem .item .desc {
        font-size: 24px;
        font-weight: 500;
        color: #767676;
        margin-bottom: 5px;
    }

    .ThongTinThem .item .title {
        font-size: 28px;
        font-weight: 600;
        color: black;
        margin-bottom: 5px;
    }

    .ThongTinThem .item .email {
        font-size: 24px;
        font-weight: 600;
        color: #35973F;
        margin-bottom: 5px;
    }

</style>
