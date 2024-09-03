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
                    <div class="card-body p-0">
                        <div class="mailbox-read-info">
                            <h4><b>{{ $email->title }}</b></h4>
                            <h6>{{ $email->isAdmin == 0 ? 'Từ: ' : 'Đến: ' }}<b>{{ $email->user->Username }}</b>
                                <span
                                    class="mailbox-read-time float-right">{{ $email->created_at->format('H:i d/m/y') }}</span>
                            </h6>
                        </div>
                        <div class="mailbox-read-message" style="min-height: 80vh; font-size: 18px">
                            {!! nl2br(e($email->content)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
