@extends('admin.layouts.main')

@section('title', 'Email')

@section('content')
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-md-2">
                <a href="{{ route('admin.pages.email.index') }}" class="btn btn-success btn-block mb-3">Hộp thư</a>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thư mục</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item active">
                                <a href="{{ route('admin.pages.email.index') }}" class="nav-link item-nav-link">
                                    <i class="fas fa-inbox"></i> Hộp thư đến
                                    <span class="badge bg-success float-right">{{ $emails_admin->total() }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.pages.email.send') }}" class="nav-link item-nav-link">
                                    <i class="far fa-envelope"></i> Đã gửi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link item-nav-link">
                                    <i class="far fa-trash-alt"></i> Thùng rác
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="col-md-10">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Soạn email</h3>
                    </div>

                    <form action="{{ route('admin.pages.email.create') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <input class="form-control" name="username" placeholder="Đến:">
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="title" placeholder="Tiêu đề:">
                            </div>
                            <div class="form-group">
                                <textarea id="compose-textarea" name="content" class="form-control" style="height: 300px"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="isAdmin" value="1">
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-right">
                                <button type="submit" class="btn btn-success"><i class="far fa-envelope"></i> Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection









<style>
    .item-nav-link:hover {
        color: #28a745 !important;
    }
</style>