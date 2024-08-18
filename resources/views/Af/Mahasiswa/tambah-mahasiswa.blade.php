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
                <a href="{{ url('mahasiswa') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>
            </div>
            <div class="card-body">
                <form action="/simpan-mahasiswa" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Jumlah Mahasiswa</label><br>
                        <input type="text" name="jmlh_mhs" class="form-control"><br>
                        <label>Prodi</label><br>
                        <select name="prodi" class="form-control">
                            <option value="">Pilih Prodi</option>
                            <option value="Sipil">Sipil</option>
                            <option value="Informatika">Informatika</option>
                            <option value="Arsitektur">Arsitektur</option>
                            <option value="Perencanaan Wilayah dan Kota">Perencanaan Wilayah dan Kota</option>
                        </select><br>

                        <input type="submit" class="btn btn-primary" name="submit" value="Simpan">

                </form>
            </div>
        </div>
    </div>
@endsection
