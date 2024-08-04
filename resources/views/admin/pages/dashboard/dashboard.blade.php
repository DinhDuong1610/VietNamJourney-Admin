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
            <!-- /.card-body -->
        </div>
    </div>

    <div class="col-4">

        <!-- DONUT CHART -->
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
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </div>
</div>
<!-- /.card -->


<div class="row">
    <div class="col-4">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Gương mặt tiêu biểu</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @yield('table-user')

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
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <div class="col-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Danh sách Quỹ Việt Nam Journey hỗ trợ các tỉnh thành</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @yield('table-quy')
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

</div>
