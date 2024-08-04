  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('admin-rs/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-rs/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin-rs/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">


  @yield('overview-content')

  <div class="card card card-success">
      @yield('card-header')
      <!-- /.card-header -->
      <div class="card-body">
          @yield('card-table-content')
      </div>
      <!-- /.card-body -->

      {{-- <div class="card-footer">
          <div class="row">
              <div class="col-8">
                  <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 1 to 10 of 57
                      entries</div>
              </div>
              <div class="col-4">
                  <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                      <ul class="pagination">
                          <li class="paginate_button page-item previous disabled" id="example1_previous"><a href="#"
                                  aria-controls="example1" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                          </li>
                          <li class="paginate_button page-item active"><a href="#" aria-controls="example1"
                                  data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
                          <li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="2"
                                  tabindex="0" class="page-link">2</a></li>
                          <li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="3"
                                  tabindex="0" class="page-link">3</a></li>
                          <li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="4"
                                  tabindex="0" class="page-link">4</a></li>
                          <li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="5"
                                  tabindex="0" class="page-link">5</a></li>
                          <li class="paginate_button page-item "><a href="#" aria-controls="example1" data-dt-idx="6"
                                  tabindex="0" class="page-link">6</a></li>
                          <li class="paginate_button page-item next" id="example1_next"><a href="#"
                                  aria-controls="example1" data-dt-idx="7" tabindex="0" class="page-link">Next</a></li>
                      </ul>
                  </div>
              </div>
          </div>
      </div> --}}
  </div>
  <!-- /.card -->
















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
