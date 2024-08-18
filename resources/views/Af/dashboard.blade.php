@extends('layouts.app')
@section('content')
    <!-- End of Topbar -->

    <!-- Begin Page Content -->
    <div class="container-fluid">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-body">
                <div class="table-responsive">
                    <h1>Selamat datang dihalaman {{(Auth::user()->level == 'af' ? 'admin fakultas' : 'admin prodi')}}</h1>
                </div>
            </div>
        </div>

    </div>


    <!-- End of Topbar -->
@endsection
