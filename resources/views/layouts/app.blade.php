<!DOCTYPE html>
<html lang="en">

<head>

    @include('partials.header')
    @yield('css')

    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    {{-- 
    <link href="{{ url('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> --}}
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('Af.Layout.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <li class="nav-item d-none d-sm-inline-block form-inline p-2 no-arrow d-sm-none btn-danger">

                    <!-- Dropdown - Messages -->
                    <div>
                        Tahun Ajaran: {{ $tahunAjaranAktif ? $tahunAjaranAktif->tahun_ajaran .' '. $tahunAjaranAktif->ganjil_genap : 'Tidak Ada' }}

                    </div>
                </li>
                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">


                    <div class="topbar-divider d-none d-sm-block"></div>

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                {{ auth()->user()->name }}
                                @if (auth()->user()->level)
                                    ({{ auth()->user()->level }})
                                @endif
                            </span>
                            <img class="img-profile rounded-circle" src="{{ url('img/undraw_profile.png') }}">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
    document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>

                    </li>

                </ul>

            </nav>
            <!-- Main Content -->
            <div id="content">
                @yield('content')
            </div>
            <!-- End of Main Content -->



        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->



    @include('partials.footer')
    @yield('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(function () {
          $('#dataTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "pageLength": 10,
            "autoWidth": true,
          });
        });

      </script> 
    {{-- <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script> --}}
    {{-- <script src="{{url('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(function () {
          $('#dataTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "pageLength": 10,
            "autoWidth": true,
          });
        });

      </script> --}}
</body>

</html>
