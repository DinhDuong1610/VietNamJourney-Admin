@yield('table-content')

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
