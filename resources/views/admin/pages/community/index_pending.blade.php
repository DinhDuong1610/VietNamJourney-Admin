@extends('admin.layouts.main')

@section('title', 'Cộng đồng')
@section('page-title', 'Cộng đồng')

@section('card-title')
    <div class="w-100 d-flex justify-content-between align-items-center ">
        <div>Cộng đồng</div>
    </div>
@endsection

@section('card-body')
    @include('admin.pages.community.postPending')
    <div class="post-list">
        @foreach ($posts as $post)
            <div class="post-item mb-3" id="post-{{ $post->Post_ID }}">
                <div class="post-header d-flex align-items-center">
                    <div class="user-avatar mr-2">
                        <img src="{{ asset($post->user->userInformation->Image) }}" alt="{{ $post->user->name }}">
                    </div>
                    <div class="user-info">
                        <div class="name">{{ $post->user->userInformation->Name }}</div>
                        <div class="time">{{ $post->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                <div class="post-content mt-2">
                    <div class="content">{{ $post->Content }}</div>
                    @if ($post->Image)
                        <div class="post-image">
                            <img src="{{ asset($post->Image) }}" alt="Post Image">
                        </div>
                    @endif
                </div>
                <div class="action d-flex justify-content-end">
                    <button class="btn btn-outline-danger delete-post mr-3" data-post-id="{{ $post->Post_ID }}">Xóa</button>
                    <button class="btn btn-outline-success approve-post" data-post-id="{{ $post->Post_ID }}">Duyệt</button>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('card-footer')
@endsection

@section('content')
    @include('admin.partials.card')
@endsection
