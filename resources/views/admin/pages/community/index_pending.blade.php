@extends('admin.layouts.main')

@section('title', 'Post Pending')       
@section('page-title', 'Người dùng')

@section('card-title')
    <div class="w-100 d-flex justify-content-between align-items-center ">
        <div>Cộng đồng</div>
    </div>
@endsection

@section('card-body')
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
                <div class="action d-flex justify-content-between">
                    <button class="btn btn-outline-danger delete-post" data-post-id="{{ $post->Post_ID }}">Xóa</button>
                    <button class="btn btn-outline-success approve-post" data-post-id="{{ $post->Post_ID }}">Duyệt</button>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-post').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (confirm('Bạn có chắc chắn muốn xóa bài viết này?')) {
                        var postId = this.getAttribute(
                        'data-post-id'); // Lấy Post_ID từ thuộc tính dữ liệu
                        if (!postId) {
                            console.error('Post ID is undefined.');
                            return;
                        }
                        var url = '{{ route('admin.pages.community.delete', ':id') }}'.replace(
                            ':id', postId);
                        var token = '{{ csrf_token() }}';

                        fetch(url, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok.');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Remove the post item from the DOM
                                    document.querySelector(`#post-${postId}`).remove();
                                    alert('Bài viết đã được xóa.');
                                } else {
                                    alert('Không thể xóa bài viết.');
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.approve-post').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                if (confirm('Bạn có chắc chắn muốn duyệt bài viết này?')) {
                    var postId = this.closest('.post-item').getAttribute('id').replace('post-', ''); // Lấy Post_ID từ thuộc tính id của post-item
                    if (!postId) {
                        console.error('Post ID is undefined.');
                        return;
                    }
                    var url = '{{ route('admin.pages.community.duyet', ':id') }}'.replace(':id', postId);
                    var token = '{{ csrf_token() }}';

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Remove the post item from the DOM or update it as necessary
                            document.querySelector(`#post-${postId}`).remove();
                            alert('Bài viết đã được duyệt.');
                        } else {
                            alert('Không thể duyệt bài viết.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    });
</script>

    <style>
        .post-list {
            padding: 0 150px;
            font-family: "montserrat", sans-serif;
        }

        .post-list .post-item {
            width: 100%;
            border: 1px solid #E5E5E5;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .post-list .post-item .post-header {
            padding: 20px 20px 0 20px;
        }

        .post-list .post-item .post-header .user-avatar {
            width: 50px;
            height: 50px;
        }

        .post-list .post-item .post-header .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .post-list .post-item .post-header .user-info .name {
            font-size: 20px;
            font-weight: 600;
            color: black;
        }

        .post-list .post-item .post-header .user-info .time {
            font-size: 16px;
            font-weight: 400;
            color: #868585;
        }

        .post-list .post-item .post-content .content {
            font-size: 18px;
            font-weight: 500;
            color: black;
            padding: 0 20px 20px 20px;
        }

        .post-list .post-item .post-content .post-image img {
            width: 100%;
        }

        .post-list .post-item .action {
            padding: 20px 20px;
            font-size: 16px;
            font-weight: 600;
        }

        .post-list .post-item .delete-post a {
            font-size: 20px;
            font-weight: 500;
            color: #868585;
            text-decoration: none;
            position: absolute;
            top: 10px;
            right: 10px;
            border: 1px solid #E5E5E5;
            height: 25px;
            width: 25px;
            border-radius: 50%;
            text-align: center;
            line-height: 25px;
        }
    </style>

@endsection

@section('card-footer')
@endsection

@section('content')
    @include('admin.partials.card')
@endsection
