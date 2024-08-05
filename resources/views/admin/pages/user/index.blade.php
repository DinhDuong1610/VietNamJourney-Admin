@extends('admin.layouts.main')

@section('title', 'User')
@section('page-title', 'Người dùng')

@section('card-title')
    <div class="w-100 d-flex justify-content-between align-items-center ">
        <div>Danh sách người dùng</div>
        <div class="d-flex">
            <input class="form-control mr-sm-2 " type="search" placeholder="Tìm kiếm" aria-label="Search">

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-file-export"></i>
                    Xuất
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item text-body" href="#"> <i class="fas fa-print"></i> In</a>
                    <a class="dropdown-item text-body" href="#"> <i class="fas fa-file-excel"></i> Excel</a>
                    <a class="dropdown-item text-body" href="#"> <i class="fas fa-file-pdf"></i> Pdf</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('table-content')
    @php
        $currentPage = $users->currentPage();
        $perPage = $users->perPage();
        $start = ($currentPage - 1) * $perPage + 1;
    @endphp
    <table class="table-users table table-striped">
        <thead>
            <tr>
                <th class="text-center" style="width: 10px"></th>
                <th style="min-width: 300px">Tên</th>
                <th style="min-width: 350px">Email</th>
                <th class="text-center" style="width: 150px">Chiến dịch</th>
                <th class="text-center" style="width: 150px">Bài viết</th>
                <th class="text-center" style="width: 150px">Quyền</th>
                <th class="text-center" style="width: 100px"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $index => $user)
                <tr>
                    <td class="text-center">{{ $start + $index }}.</td>
                    <td class="name">
                        <img class="avatar" src="{{ asset($user->userInformation->Image) }}" alt="avatar"
                            class="img-circle img-size-32 mr-2">
                        <div class="inner-name">
                            <div class="fullname">{{ $user->userInformation->Name ?? 'N/A' }}</div>
                            <div class="username">{{ $user->userInformation->Username ?? 'N/A' }}</div>
                        </div>
                    </td>
                    <td>{{ $user->userInformation->Email }}</td>
                    <td class="text-center">{{ $user->volunteer->count() }}</td> <!-- Số lượng chiến dịch -->
                    <td class="text-center">{{ $user->post->count() }}</td> <!-- Số lượng bài viết -->
                    <td class="text-center">
                        @if ($user->is_admin())
                            <span class="badge badge-pill badge-primary w-75"><span class="d-inline-block py-2">Admin</span></span>
                        @elseif($user->userInformation->check == 1)
                            <span class="badge badge-pill badge-success w-75"><span class="d-inline-block py-2">Professional</span></span>
                        @else
                            <span class="badge badge-pill badge-secondary w-75"><span class="d-inline-block py-2">User</span class="d-inline-block py-2"></span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Hiển thị các liên kết phân trang -->
    <div class="d-flex justify-content-center mt-3">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">


    <!-- Thêm CSS tùy chỉnh -->
    <style>
        .pagination .page-link {
            color: #28a745;
            /* Màu xanh lá cho văn bản */
        }

        .pagination .page-item.active .page-link {
            background-color: #28a745;
            /* Màu nền xanh lá cho trang hiện tại */
            border-color: #28a745;
            /* Viền màu xanh lá cho trang hiện tại */
        }

        .pagination .page-link:hover {
            color: #218838;
            /* Màu xanh lá đậm hơn khi hover */
        }

        .pagination .page-link:focus {
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            /* Bóng xanh lá khi focus */
        }
    </style>
@endsection

@section('card-body')
    @include('admin.pages.user.users')
@endsection

@section('card-footer')
@endsection

@section('content')
    @include('admin.partials.card')
@endsection
