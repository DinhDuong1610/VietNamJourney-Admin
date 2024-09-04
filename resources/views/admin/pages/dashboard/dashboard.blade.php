@yield('overview-content')

<!-- BAR CHART -->
<div class="row">
    <div class="col-8">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Chiến dịch</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="barChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">Người dùng</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="donutChart"
                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-4">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Gương mặt tiêu biểu</h3>
            </div>
            <div class="card-body">
                @yield('table-user')
            </div>
        </div>
    </div>

    <div class="col-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Danh sách Quỹ Việt Nam Journey hỗ trợ các tỉnh thành</h3>
            </div>
            <div class="card-body">
                @yield('table-quy')
            </div>
        </div>
    </div>

</div>



<style>
    .table-users .name {
        display: flex;
        align-items: center;
        vertical-align: center;
    }

    .table-users .name .avatar {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
    }

    .table-users .name .inner-name {
        margin-left: 10px;
    }

    .table-users .name .inner-name .fullname {
        font-size: 18px;
        font-weight: 600;
        line-height: 100%;
        margin-bottom: 3px;
    }

    .table-users .name .inner-name .username {
        font-size: 15px;
        font-weight: 500;
        line-height: 100%;
        color: #616161;
    }
</style>

<style>
    .paginate-dashboard-user .pagination .page-link {
        color: #FFC107;
    }

    .paginate-dashboard-user .pagination .page-item.active .page-link {
        background-color: #FFC107;
        border-color: #FFC107;
        color: white;
    }

    .paginate-dashboard-user .pagination .page-link:hover {
        color: #FFC107;
    }

    .paginate-dashboard-user .pagination .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(162, 175, 14, 0.25);
    }
</style>
<style>
    .paginate-dashboard-quy .pagination .page-link {
        color: #007BFF;
    }

    .paginate-dashboard-quy .pagination .page-item.active .page-link {
        background-color: #007BFF;
        border-color: #007BFF;
        color: white;
    }

    .paginate-dashboard-quy .pagination .page-link:hover {
        color: #007BFF;
    }

    .paginate-dashboard-quy .pagination .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(8, 61, 139, 0.25);
    }
</style>
