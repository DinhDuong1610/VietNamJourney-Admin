@extends('admin.layouts.main')

@section('title', 'Email')

@section('content')
    <div class="row mt-3">
        <div class="col-md-2">
            <a href="{{ route('admin.pages.email.compose') }}" class="btn btn-success btn-block mb-3">Soạn email</a>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Thư mục</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-folder card-body p-0">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item active">
                            <a href="{{ route('admin.pages.email.index') }}" class="nav-link">
                                <i class="fas fa-inbox"></i> Hộp thư đến
                                <span class="badge bg-success float-right">{{ $emails_admin->total() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.pages.email.send') }}" class="nav-link">
                                <i class="far fa-envelope"></i> Đã gửi
                                {{-- <span class="badge bg-success float-right">{{ $emails_send->total() }}</span> --}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-trash-alt"></i> Thùng rác
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-10">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">Hộp thư</h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" placeholder="Tìm kiếm">
                            <div class="input-group-append">
                                <div class="btn btn-success">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive mailbox-messages">
                        <table class="table-email table table-hover table-striped">
                            <tbody>
                                @foreach ($emails as $email)
                                    <tr class="d-flex justify-content-between" data-email-id="{{ $email->id }}"
                                        {{-- onclick="location.href='{{ route('admin.pages.email.read', $email->id) }}'" --}}>
                                        <td class="mailbox-check">
                                            <div class="icheck-primary">
                                                <input type="checkbox" value="" id="check{{ $email->id }}">
                                                <label for="check{{ $email->id }}"></label>
                                            </div>
                                        </td>
                                        {{-- <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td> --}}
                                        <td class="mailbox-name"><a
                                                href='{{ route('admin.pages.email.read', $email->id) }}'>{{ $email->user->Username }}</a>
                                        </td>
                                        <td class="mailbox-subject">
                                            <div
                                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: {{ $email->status == 0 && $email->isAdmin == 0 ? '600' : 'normal' }};">
                                                <b>{{ $email->title }}</b> - {{ $email->content }}
                                            </div>
                                        </td>
                                        <td class="mailbox-date">{{ $email->created_at->format('H:i d/m/y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- /.table -->

                        <style>
                            .table-email tr {
                                cursor: pointer;
                            }

                            .table-email tr .mailbox-check {
                                width: 50px;
                            }

                            .table-email tr .mailbox-name {
                                font-size: 18px;
                                font-weight: 500;
                                line-height: 100%;
                                width: 200px;
                                display: flex;
                                align-items: center;
                            }

                            .table-email tr .mailbox-name a {
                                color: #28a745;
                            }

                            .table-email tr .mailbox-subject {
                                font-size: 18px;
                                font-weight: 400;
                                line-height: 100%;
                                max-width: 700px;
                                display: flex;
                                align-items: center;
                            }

                            .table-email tr .mailbox-date {
                                font-size: 16px;
                                font-weight: 400;
                                line-height: 100%;
                                color: #606060;
                                display: flex;
                                align-items: center;
                            }
                        </style>
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer p-0">
                    <div class="mailbox-controls">
                        <div class="d-flex justify-content-between align-items-center mt-2 px-4">
                            <b>{{ $emails->firstItem() }}-{{ $emails->lastItem() }}/{{ $emails->total() }}</b>
                            {{ $emails->links('pagination::bootstrap-4') }}
                        </div>
                        <!-- /.float-right -->
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.table-email tr').forEach(function(row) {
                row.addEventListener('click', function() {
                    const emailId = this.dataset.emailId;

                    // Gửi yêu cầu PUT để cập nhật trạng thái
                    fetch(`{{ route('admin.pages.email.readed', '') }}/${emailId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status: 1
                        })
                    }).then(response => {
                        if (response.ok) {
                            // Chuyển hướng đến trang chi tiết email sau khi cập nhật
                            window.location.href =
                                `{{ route('admin.pages.email.read', '') }}/${emailId}`;
                        } else {
                            return response.json().then(data => {
                                console.error('Error:', data.error);
                            });
                        }
                    }).catch(error => {
                        console.error('Fetch error:', error);
                    });
                });
            });
        });
    </script>

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
