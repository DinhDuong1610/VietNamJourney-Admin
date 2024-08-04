@extends('admin.layouts.main')

@section('title', 'Campaign Detail')

@section('page-title', 'Chiến dịch chi tiết')

@section('content')
    <div class="PageDetail">
        <div class="header"
            style="background-image: url('{{ asset($campaign->image) }}'); background-size: cover; background-position: center; background-color: rgba(0, 0, 0, 0.5); background-blend-mode: multiply;">
            <p class="title">Dự án & Chiến dịch</p>
            <p class="camp-id">FP{{ $campaign->id }}</p>
            <p class="desc">{{ $campaign->name }}</p>
        </div>
        <div class="menu">
            <ul>
                <li onclick="scrollToSection('tongQuan')">Tổng quan</li>
                <li onclick="scrollToSection('tacDong')">Tác động</li>
        <li onclick="scrollToSection('chiTiet')">Chi tiết</li>
        <li onclick="scrollToSection('thongTinThem')">Thông tin thêm</li>
            </ul>
        </div>
        <div id="tongQuan">@include('admin.pages.campaign.campaignDetail.tongquan.index', ['campaign' => $campaign])</div>
        <div id="tacDong">@include('admin.pages.campaign.campaignDetail.tacdong.index', ['campaign' => $campaign])</div>
    <div id="chiTiet">@include('admin.pages.campaign.campaignDetail.chitiet.index', ['campaign' => $campaign])</div>
    <div id="thongTinThem">@include('admin.pages.campaign.campaignDetail.thongtinthem.index', ['campaign' => $campaign])</div>
    </div>

    <script>
        function scrollToSection(section) {
            const element = document.getElementById(section);
            const offset = 55;
            const elementPosition = element.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth',
            });

            document.querySelectorAll('.menu ul li').forEach(li => {
                li.classList.remove('active');
            });
            document.querySelector(`.menu ul li[onclick="scrollToSection('${section}')"]`).classList.add('active');
        }
    </script>


    <style>
        .PageDetail {
            background-color: #F6F7F8;
            padding-bottom: 100px;
            font-family: 'montserrat', sans-serif;
        }

        .PageDetail .header {
            height: 600px;
            padding-top: 250px;
            padding-left: 200px;
            padding-right: 200px;
        }

        .PageDetail .header .title {
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin-bottom: 0;
        }

        .PageDetail .header .camp-id {
            font-size: 80px;
            font-weight: 700;
            color: white;
            margin-bottom: 0;
        }

        .PageDetail .header .desc {
            font-size: 36px;
            font-weight: 600;
            color: white;
        }

        .PageDetail .menu {
            width: 100%;
            position: sticky;
            top: 0px;
            
        }

        .PageDetail .menu ul {
            display: flex;
            flex-wrap: wrap;
            padding-left: 0px;
            background-color: #1F8E2B;
            margin-bottom: 0;
        }

        .PageDetail .menu ul li {
            list-style-type: none;
            width: 25%;
            font-size: 20px;
            font-weight: 600;
            padding: 12px 0px;
            text-align: center;
            color: white;
            cursor: pointer;
        }

        .PageDetail .menu ul li:hover {
            background-color: #006322;
        }

        @media screen and (max-width: 1600px) {
            .PageDetail {
                background-color: #F6F7F8;
                padding-bottom: 100px;
            }

            .PageDetail .header {
                height: 600px;
                padding-top: 250px;
                padding-left: 200px;
                padding-right: 200px;
            }

            .PageDetail .header .title {
                font-size: 26px;
                font-weight: 700;
                color: white;
                margin-bottom: 0;
            }

            .PageDetail .header .camp-id {
                font-size: 60px;
                font-weight: 700;
                color: white;
                margin-bottom: 0;
            }

            .PageDetail .header .desc {
                font-size: 26px;
                font-weight: 600;
                color: white;
            }

            .PageDetail .menu {
                width: 85%;





            }

            .PageDetail .menu ul {
                display: flex;
                flex-wrap: wrap;
                padding-left: 200px;
                background-color: #1F8E2B;
            }

            .PageDetail .menu ul li {
                list-style-type: none;
                width: 25%;
                font-size: 16px;
                font-weight: 600;
                padding: 12px 0px;
                text-align: center;
                color: white;
                cursor: pointer;
            }

        }
    </style>
@endsection
