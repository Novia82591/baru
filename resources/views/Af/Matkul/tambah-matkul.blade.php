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
                <a href="{{ url('matkul') }}" class="btn btn-primary btn-icon-split btn-sm">
                    <span class="text">Kembali</span>
                </a>
            </div>
            <div class="card-body">
                <form action="/simpan-matkul" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Kode Mata Kuliah</label><br>
                        <input type="text" name="kd_matkul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Mata Kuliah</label><br>
                        <input type="text" name="nm_matkul" class="form-control">
                    </div>
                    <label>Semester</label><br>
                    <select name="semester" class="form-control">
                        <option value="">Pilih Semester</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                    </select><br>
                    <label>Prodi</label><br>
                    <select name="prodi" class="form-control">
                        <option value="">Pilih Prodi</option>
                        <option value="Umum">Umum</option>
                        <option value="Sipil">Sipil</option>
                        <option value="Informatika">Informatika</option>
                        <option value="Arsitektur">Arsitektur</option>
                        <option value="Perencanaan Wilayah dan Kota">Perencanaan Wilayah dan Kota</option>
                    </select><br>
                    <label>Sks</label><br>
                    <select name="sks" class="form-control">
                        <option value="">Pilih Sks</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                    </select><br>

                    <input type="submit" class="btn btn-primary" name="submit" value="Simpan">

                </form>
            </div>
        </div>
    </div>
@endsection
