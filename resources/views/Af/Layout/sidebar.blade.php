<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('logo.png') }}" alt="UMB Logo" style="width: 40px;">
        </div>
        <div class="sidebar-brand-text mx-3">
            @if (auth()->user()->level == 'af')
                Fakultas Teknik
            @elseif (auth()->user()->level == 'ap')
                {{ auth()->user()->prodi }}
            @endif
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ url('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>



    @if (auth()->user()->level == 'af')
        <!-- Nav Item - Charts -->
        <li class="nav-item">
            <a class="nav-link" href="/dosen">
                <i class="fas fa-fw fa-table"></i>
                <span>Dosen</span></a>
        </li>

        <!-- Nav Item - Charts -->
    @endif
    <li class="nav-item">
        <a class="nav-link" href="/ruang">
            <i class="fas fa-fw fa-table"></i>
            <span>Ruang</span></a>
    </li>
    <!-- Nav Item - Charts -->

    <li class="nav-item">
        <a class="nav-link" href="/matkul">
            <i class="fas fa-fw fa-table"></i>
            <span>Mata Kuliah</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('kelas.index') }}">
            <i class="fas fa-fw fa-folder"></i>
            <span>Struktur Mengajar</span></a>
    </li>
    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('jadwal.index') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Jadwal</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ url('jadwal-history') }}">
            <i class="fas fa-fw fa-folder"></i>
            <span>Riwayat Random Jadwal</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('jadwal.riwayat') }}">
            <i class="fas fa-fw fa-folder"></i>
            <span>Riwayat Jadwal</span></a>
    </li>
    @if (auth()->user()->level == 'af')
        <li class="nav-item">
            <a class="nav-link" href="{{ route('tahunajaran.index') }}">
                <i class="fas fa-fw fa-folder"></i>
                <span>Tahun Ajaran</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="fas fa-fw fa-folder"></i>
                <span>User</span></a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
