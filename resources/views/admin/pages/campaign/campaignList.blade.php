  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('admin-rs/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-rs/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-rs/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">


  @yield('overview-content')

  <div class="card card card-success">
      @yield('card-header')
      <div class="card-body">
          @yield('card-table-content')
      </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/json_data_vn_units.json')
            .then(response => response.json())
            .then(provinces => {
                const provinceSelect = document.getElementById('provinceSelect');

                provinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.Name;
                    option.textContent = province.Name;

                    // Lấy giá trị province từ query parameters
                    const urlParams = new URLSearchParams(window.location.search);
                    const selectedProvince = urlParams.get('province') || 'Tất cả';

                    // Kiểm tra nếu giá trị của option khớp với giá trị đã chọn
                    if (province.Name === selectedProvince) {
                        option.selected = true;
                    }
                    provinceSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading provinces:', error));
    });


    document.getElementById('provinceSelect').addEventListener('change', function() {
        selectedProvince = this.value;
        let url;

        if (selectedProvince === 'Tất cả') {
            url = `{{ route('admin.pages.campaign.list') }}`;
        } else {
            url =
                `{{ route('admin.pages.campaign.list.searchByProvince') }}?province=${encodeURIComponent(selectedProvince)}`;
        }
        window.location.href = url;
    });

    document.getElementById('campaignIdInput').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            let campaignId = this.value.trim();

            if (campaignId.startsWith('fp') || campaignId.startsWith('FP') || campaignId.startsWith('Fp')) {
                campaignId = campaignId.substring(2);
            }

            if (campaignId) {
                const url =
                    `{{ route('admin.pages.campaign.list.searchById') }}?campaignId=${encodeURIComponent(campaignId)}`;
                window.location.href = url;
            }
        }
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#example1 tbody tr').forEach(function(row) {
            row.addEventListener('click', function() {
                var campaignId = this.getAttribute('data-campaign-id');
                var url =
                    `{{ route('admin.pages.campaign.detail', ['campaignId' => '__campaignId__']) }}`
                    .replace('__campaignId__',
                        campaignId);
                window.location.href = url;
            });
        });
    });
</script>
















  <!-- DataTables  & Plugins -->
  <script src="{{ asset('admin-rs/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('admin-rs/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('admin-rs/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('admin-rs/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('admin-rs/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('admin-rs/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('admin-rs/plugins/jszip/jszip.min.js') }}"></script>
  <script src="{{ asset('admin-rs/plugins/pdfmake/pdfmake.min.js') }}"></script>
  <script src="{{ asset('admin-rs/plugins/pdfmake/vfs_fonts.js') }}"></script>
  <script src="{{ asset('admin-rs/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('admin-rs/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('admin-rs/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
  <!-- Page specific script -->
  <script>
      $(function() {
          $("#example1").DataTable({
              "responsive": true,
              "lengthChange": false,
              "autoWidth": false,
              "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
          }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
          $('#example2').DataTable({
              "paging": true,
              "lengthChange": false,
              "searching": false,
              "ordering": true,
              "info": true,
              "autoWidth": false,
              "responsive": true,
          });
      });
  </script>
