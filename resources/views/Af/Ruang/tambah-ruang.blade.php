@extends('layouts.app')
@section('content')
    <!-- End of Topbar -->

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Data</h6>
                <div class="mb-4"></div>
                <a href="{{ url('ruang') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>
            </div>
            <div class="card-body">
                <form action="/simpan-ruang" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Kode Ruang</label><br>
                        <input type="text" name="kd_ruang" required class="form-control">
                    </div>

                    <label>Nama Ruang</label><br>
                    <input type="text" name="nama_ruang" class="form-control"><br>
                    <label>Prodi</label><br>
                    <select name="prodi" class="form-control">
                        <option value="">Pilih Prodi</option>
                            <option value="Umum">Umum</option>
                        @if (auth()->user()->level == 'af')
                            <option value="Sipil">Sipil</option>
                            <option value="Informatika">Informatika</option>
                            <option value="Arsitektur">Arsitektur</option>
                            <option value="Perencanaan Wilayah dan Kota">Perencanaan Wilayah dan Kota</option>
                        @elseif (auth()->user()->level == 'ap')
                            <option value="{{ auth()->user()->prodi }}">{{ auth()->user()->prodi }}</option>
                        @endif
                    </select>
                    
                    <br>
                    <label>Kapasitas</label><br>
                    <input type="text" name="kapasitas" class="form-control"><br>

                    <input type="submit" class="btn btn-primary" name="submit" value="Simpan">

                </form>
            </div>
        </div>
    </div>
@endsection
