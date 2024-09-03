@extends('admin.layouts.main')

@section('title', 'Người dùng')
@section('page-title', 'Người dùng')

@section('card-title')
    <div class="w-100 d-flex justify-content-between align-items-center ">
        <div>Danh sách người dùng</div>
        <div class="d-flex">
            <input class="form-control mr-sm-2 " type="search" placeholder="Tìm kiếm" aria-label="Search">
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
                    <td class="text-center text-bold">{{ $user->volunteer->count() }}</td>
                    <td class="text-center text-bold">{{ $user->post->count() }}</td>
                    <td class="text-center">
                        @if ($user->is_admin())
                            <span class="badge badge-pill badge-primary w-75"><span
                                    class="d-inline-block py-2">Admin</span></span>
                        @elseif($user->userInformation->check == 1)
                            <span class="badge badge-pill badge-success w-75"><span
                                    class="d-inline-block py-2">Professional</span></span>
                        @else
                            <span class="badge badge-pill badge-secondary w-75"><span class="d-inline-block py-2">User</span
                                    class="d-inline-block py-2"></span>
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

    <!-- Panigation -->
    <div class="d-flex justify-content-center mt-3">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('card-body')
    @include('admin.pages.user.users')
@endsection

@section('card-footer')
@endsection

@section('content')
    @include('admin.partials.card')
@endsection
