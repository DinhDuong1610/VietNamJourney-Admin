<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-post').forEach(function (deleteLink) {
            deleteLink.addEventListener('click', function (e) {
                e.preventDefault();
                
                if (confirm('Bạn có chắc chắn muốn xóa bài viết này?')) {
                    var postId = this.dataset.postId;
                    var url = `{{ route('admin.pages.community.delete', ':id') }}`.replace(':id', postId);
                    var token = '{{ csrf_token() }}';

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        }
                    })
                    .then(response => response.json())
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

    .post-list .post-item .post-options a{
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